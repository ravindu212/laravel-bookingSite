<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use LazilyRefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_public_site_does_not_show_hard_coded_stays(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('No stays yet')
            ->assertDontSee('Sigiriya Rock Garden Stay')
            ->assertDontSee('Ella Nine Arches Lodge')
            ->assertDontSee('Mirissa Coconut Bay Inn');
    }

    public function test_admin_can_create_a_travel_stay(): void
    {
        $this->actingAs(User::factory()->create());

        $response = $this->post(route('admin.hotels.store'), [
            'name' => 'Kandy Lake View Villa',
            'description' => 'A simple stay close to the Temple of the Tooth and Kandy Lake.',
            'image_url' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Kandy%20Sri%20Lanka%20Temple%20of%20the%20tooth.jpg',
            'location' => 'Kandy, Central Province',
            'phone' => '+94 81 222 1234',
            'email' => 'hello@kandyvilla.test',
            'website' => 'https://www.srilanka.travel/',
        ]);

        $response
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('hotels', [
            'name' => 'Kandy Lake View Villa',
            'location' => 'Kandy, Central Province',
            'email' => 'hello@kandyvilla.test',
        ]);
    }

    public function test_created_travel_stay_appears_on_public_site(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('admin.hotels.store'), [
            'name' => 'Galle Fort Courtyard Stay',
            'description' => 'A practice listing beside lighthouse walks, old walls, and southern sea breeze.',
            'image_url' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Galle%20Fort%20view%20with%20the%20sunset.jpg',
            'location' => 'Galle Fort, Southern Province',
            'phone' => '+94 91 222 5678',
            'email' => 'stay@gallefort.test',
            'website' => 'https://www.srilanka.travel/',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Galle Fort Courtyard Stay')
            ->assertSee('Galle Fort, Southern Province')
            ->assertSee('stay@gallefort.test');
    }

    public function test_public_card_links_to_hotel_booking_page(): void
    {
        $hotel = Hotel::create([
            'name' => 'Hiriketiya Surf Stay',
            'description' => 'A simple surf stay near the bay.',
            'location' => 'Hiriketiya, Southern Province',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee(route('hotels.booking', $hotel));
    }

    public function test_public_admin_link_opens_practice_login_page(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee(route('admin.login'));

        $this->get(route('admin.login'))
            ->assertOk()
            ->assertSee('Sign in')
            ->assertSee('Login to manage stays and booking requests.')
            ->assertSee('action="'.route('admin.login.store').'"', false)
            ->assertSee(route('admin.register'))
            ->assertDontSee('Open practice dashboard');
    }

    public function test_guest_is_redirected_to_login_before_admin_dashboard(): void
    {
        $this->get(route('dashboard'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_register_and_password_is_hashed(): void
    {
        $response = $this->post(route('admin.register.store'), [
            'name' => 'Travel Admin',
            'email' => 'admin@ceylontrails.test',
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        $user = User::where('email', 'admin@ceylontrails.test')->first();

        $this->assertNotNull($user);
        $this->assertTrue(Hash::check('secret-password', $user->password));
        $this->assertNotSame('secret-password', $user->password);
    }

    public function test_admin_can_login_with_valid_details(): void
    {
        $user = User::factory()->create([
            'email' => 'owner@ceylontrails.test',
            'password' => Hash::make('secret-password'),
        ]);

        $response = $this->post(route('admin.login.store'), [
            'email' => 'owner@ceylontrails.test',
            'password' => 'secret-password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_cannot_login_with_wrong_password(): void
    {
        User::factory()->create([
            'email' => 'owner@ceylontrails.test',
            'password' => Hash::make('secret-password'),
        ]);

        $this->post(route('admin.login.store'), [
            'email' => 'owner@ceylontrails.test',
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_public_site_shows_static_travel_moods_section(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Simple Sri Lankan travel moods')
            ->assertSee('Tea country mornings')
            ->assertSee('Old streets by the sea')
            ->assertSee('Wild south days');
    }

    public function test_hotel_booking_page_displays_hotel_details(): void
    {
        $hotel = Hotel::create([
            'name' => 'Anuradhapura Heritage Rest',
            'description' => 'Close to ancient temples and calm evening walks.',
            'location' => 'Anuradhapura, North Central Province',
            'phone' => '+94 25 222 1234',
            'email' => 'book@heritagerest.test',
            'website' => 'https://www.srilanka.travel/',
        ]);

        $this->get(route('hotels.booking', $hotel))
            ->assertOk()
            ->assertSee('Booking page')
            ->assertSee('Anuradhapura Heritage Rest')
            ->assertSee('book@heritagerest.test')
            ->assertSee('Visit hotel website');
    }

    public function test_customer_can_send_a_booking_request(): void
    {
        $hotel = Hotel::create([
            'name' => 'Ella Mountain Stay',
            'description' => 'A quiet stay near tea country.',
            'location' => 'Ella, Uva Province',
        ]);

        $response = $this->post(route('hotels.booking.store', $hotel), [
            'customer_name' => 'Nimal Perera',
            'customer_email' => 'nimal@example.test',
            'customer_phone' => '+94 77 555 1234',
            'check_in' => '2026-10-01',
            'check_out' => '2026-10-04',
            'guests' => 3,
            'message' => 'Need a room with a nice view.',
        ]);

        $response
            ->assertRedirect(route('hotels.booking', $hotel))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('bookings', [
            'hotel_id' => $hotel->id,
            'customer_name' => 'Nimal Perera',
            'customer_email' => 'nimal@example.test',
            'guests' => 3,
        ]);
    }

    public function test_booking_request_requires_useful_details(): void
    {
        $hotel = Hotel::create([
            'name' => 'Galle Test Stay',
            'location' => 'Galle, Southern Province',
        ]);

        $this->post(route('hotels.booking.store', $hotel), [])
            ->assertSessionHasErrors([
                'customer_name',
                'customer_email',
                'check_in',
                'check_out',
                'guests',
            ]);
    }

    public function test_admin_can_view_booking_requests(): void
    {
        $this->actingAs(User::factory()->create());

        $hotel = Hotel::create([
            'name' => 'Sigiriya Garden Villa',
            'location' => 'Sigiriya, Central Province',
        ]);

        Booking::create([
            'hotel_id' => $hotel->id,
            'customer_name' => 'Asha Silva',
            'customer_email' => 'asha@example.test',
            'customer_phone' => '+94 71 222 3344',
            'check_in' => '2026-11-10',
            'check_out' => '2026-11-12',
            'guests' => 2,
            'message' => 'Late check-in please.',
        ]);

        $this->get(route('admin.bookings.index'))
            ->assertOk()
            ->assertSee('Sigiriya Garden Villa')
            ->assertSee('Asha Silva')
            ->assertSee('asha@example.test')
            ->assertSee('Late check-in please.');
    }

    public function test_admin_can_open_the_edit_form_for_a_travel_stay(): void
    {
        $this->actingAs(User::factory()->create());

        $hotel = Hotel::create([
            'name' => 'Original Stay',
            'description' => 'Original description.',
            'location' => 'Matara, Southern Province',
        ]);

        $this->get(route('admin.hotels.edit', $hotel))
            ->assertOk()
            ->assertSee('Edit stay')
            ->assertSee('Original Stay');
    }

    public function test_admin_can_update_a_travel_stay(): void
    {
        $this->actingAs(User::factory()->create());

        $hotel = Hotel::create([
            'name' => 'Old Stay',
            'description' => 'Old description.',
            'location' => 'Old location',
        ]);

        $response = $this->put(route('admin.hotels.update', $hotel), [
            'name' => 'Updated Nuwara Eliya Bungalow',
            'description' => 'Fresh tea-country description.',
            'image_url' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Nuwara%20Eliya%20-%20Lake%20Gregory.jpg',
            'location' => 'Nuwara Eliya, Central Province',
            'phone' => '+94 52 222 4567',
            'email' => 'stay@nuwaraeliya.test',
            'website' => 'https://www.srilanka.travel/',
        ]);

        $response
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('hotels', [
            'id' => $hotel->id,
            'name' => 'Updated Nuwara Eliya Bungalow',
            'location' => 'Nuwara Eliya, Central Province',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Updated Nuwara Eliya Bungalow')
            ->assertDontSee('Old Stay');
    }

    public function test_admin_can_delete_a_travel_stay(): void
    {
        $this->actingAs(User::factory()->create());

        $hotel = Hotel::create([
            'name' => 'Delete Me Stay',
            'description' => 'This stay should be removed.',
            'location' => 'Bentota, Southern Province',
        ]);

        $response = $this->delete(route('admin.hotels.destroy', $hotel));

        $response
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('status');

        $this->assertDatabaseMissing('hotels', [
            'id' => $hotel->id,
        ]);
    }
}
