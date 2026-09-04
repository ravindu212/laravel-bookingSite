<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Hotel;
use App\Models\HotelInventory;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
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

    public function test_logged_in_admin_links_go_to_dashboard(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/')
            ->assertOk()
            ->assertSee(route('dashboard'))
            ->assertDontSee(route('admin.login'));

        $this->get(route('admin.login'))
            ->assertRedirect(route('dashboard'));

        $this->get(route('admin.register'))
            ->assertRedirect(route('dashboard'));
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

    public function test_public_site_shows_faq_section(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Questions before you book')
            ->assertSee('How do I book a stay?')
            ->assertSee('Can I contact hotels directly?')
            ->assertSee('Are these real listings?')
            ->assertSee('Can I cancel a booking request?');
    }

    public function test_public_site_shows_customer_reviews_section(): void
    {
        Review::create([
            'customer_name' => 'Kavindi Fernando',
            'location' => 'Galle, Sri Lanka',
            'rating' => 5,
            'comment' => 'The booking request was simple and the stay ideas felt local.',
            'is_approved' => true,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('What travellers say')
            ->assertSee('Add review')
            ->assertSee('Kavindi Fernando')
            ->assertSee('Galle, Sri Lanka')
            ->assertSee('The booking request was simple and the stay ideas felt local.');
    }

    public function test_customer_can_add_a_review(): void
    {
        Livewire::test('customer-reviews')
            ->set('customerName', 'Dinesh Perera')
            ->set('location', 'Ella, Sri Lanka')
            ->set('rating', '4')
            ->set('comment', 'Nice practice travel site with useful hotel details.')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSee('Thank you. Your review will show after admin approval.');

        $this->assertDatabaseHas('reviews', [
            'customer_name' => 'Dinesh Perera',
            'location' => 'Ella, Sri Lanka',
            'rating' => 4,
            'comment' => 'Nice practice travel site with useful hotel details.',
            'is_approved' => false,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('Dinesh Perera')
            ->assertDontSee('Nice practice travel site with useful hotel details.');
    }

    public function test_customer_can_add_a_review_without_livewire_javascript(): void
    {
        $response = $this->post(route('reviews.store'), [
            'customer_name' => 'Tharushi Silva',
            'location' => 'Kandy, Sri Lanka',
            'rating' => 5,
            'comment' => 'Lovely simple travel guide for Sri Lankan stays.',
        ]);

        $response
            ->assertRedirect(route('home'))
            ->assertSessionHas('review_status');

        $this->assertDatabaseHas('reviews', [
            'customer_name' => 'Tharushi Silva',
            'is_approved' => false,
        ]);
    }

    public function test_old_reviews_url_redirects_back_to_home_reviews_section(): void
    {
        $this->get('/reviews')
            ->assertRedirect('/#reviews');
    }

    public function test_customer_review_requires_useful_details(): void
    {
        Livewire::test('customer-reviews')
            ->call('save')
            ->assertHasErrors([
                'customerName' => 'required',
                'rating' => 'required',
                'comment' => 'required',
            ]);
    }

    public function test_admin_can_view_pending_customer_reviews(): void
    {
        $this->actingAs(User::factory()->create());

        Review::create([
            'customer_name' => 'Malith Jayasuriya',
            'location' => 'Kandy, Sri Lanka',
            'rating' => 5,
            'comment' => 'Lovely travel ideas for a small Sri Lankan trip.',
            'is_approved' => false,
        ]);

        $this->get(route('admin.reviews.index'))
            ->assertOk()
            ->assertSee('Malith Jayasuriya')
            ->assertSee('Pending')
            ->assertSee('Approve');
    }

    public function test_admin_can_approve_customer_review(): void
    {
        $this->actingAs(User::factory()->create());

        $review = Review::create([
            'customer_name' => 'Sanduni Silva',
            'location' => 'Matara, Sri Lanka',
            'rating' => 5,
            'comment' => 'Easy booking request and clear stay details.',
            'is_approved' => false,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('Sanduni Silva');

        $response = $this->patch(route('admin.reviews.approve', $review));

        $response
            ->assertRedirect(route('admin.reviews.index'))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'is_approved' => true,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Sanduni Silva')
            ->assertSee('Easy booking request and clear stay details.');
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

        HotelInventory::factory()->for($hotel)->create([
            'category' => 'Foods',
            'menu_type' => 'Breakfast',
            'name' => 'Village breakfast',
            'description' => 'Milk rice, lunu miris, and fresh fruit.',
            'price' => 2500,
            'people_count' => 2,
        ]);

        $this->get(route('hotels.booking', $hotel))
            ->assertOk()
            ->assertSee('Booking page')
            ->assertSee('Anuradhapura Heritage Rest')
            ->assertSee('book@heritagerest.test')
            ->assertSee('Visit hotel website')
            ->assertSee('Included with this stay')
            ->assertSee('Village breakfast')
            ->assertSee('Breakfast')
            ->assertSee('LKR 2,500.00')
            ->assertSee('2 people');
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

    public function test_admin_can_open_hotel_inventory_page(): void
    {
        $this->actingAs(User::factory()->create());

        $hotel = Hotel::factory()->create(['name' => 'Ella Inventory Stay']);
        HotelInventory::factory()->for($hotel)->create([
            'category' => 'Foods',
            'menu_type' => 'Breakfast',
            'name' => 'Sri Lankan breakfast',
            'description' => 'String hoppers, dhal curry, and coconut sambol.',
            'price' => 1800,
            'people_count' => 1,
        ]);

        $this->get(route('admin.hotels.inventories', $hotel))
            ->assertOk()
            ->assertSee('Hotel inventory')
            ->assertSee('Ella Inventory Stay')
            ->assertSee('Sri Lankan breakfast')
            ->assertSee('LKR 1,800.00');
    }

    public function test_admin_can_add_inventory_manually(): void
    {
        $this->actingAs(User::factory()->create());

        $hotel = Hotel::factory()->create();

        $response = $this->post(route('admin.hotels.inventories.store', $hotel), [
            'category' => 'Foods',
            'menu_type' => 'Lunch',
            'name' => 'Rice and curry lunch',
            'description' => 'Chicken curry, dhal, mallung, and papadam.',
            'price' => 3500,
            'people_count' => 2,
        ]);

        $response
            ->assertRedirect(route('admin.hotels.inventories', $hotel))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('hotel_inventories', [
            'hotel_id' => $hotel->id,
            'category' => 'Foods',
            'menu_type' => 'Lunch',
            'name' => 'Rice and curry lunch',
            'price' => 3500,
            'people_count' => 2,
        ]);
    }

    public function test_admin_can_import_inventory_from_csv(): void
    {
        $this->actingAs(User::factory()->create());

        $hotel = Hotel::factory()->create();
        $file = UploadedFile::fake()->createWithContent(
            'inventory.csv',
            "category,menu_type,name,description,price,people_count\nFoods,Breakfast,Sri Lankan breakfast,String hoppers and dhal,1800,1\nPackage,Pool,Pool access,Evening pool pass,2500,2\n"
        );

        $response = $this->post(route('admin.hotels.inventories.import', $hotel), [
            'inventory_file' => $file,
        ]);

        $response
            ->assertRedirect(route('admin.hotels.inventories', $hotel))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('hotel_inventories', [
            'hotel_id' => $hotel->id,
            'category' => 'Foods',
            'menu_type' => 'Breakfast',
            'name' => 'Sri Lankan breakfast',
            'price' => 1800,
            'people_count' => 1,
        ]);

        $this->assertDatabaseHas('hotel_inventories', [
            'hotel_id' => $hotel->id,
            'category' => 'Package',
            'menu_type' => 'Pool',
            'name' => 'Pool access',
            'price' => 2500,
            'people_count' => 2,
        ]);
    }

    public function test_admin_can_import_csv_detected_as_excel_file(): void
    {
        $this->actingAs(User::factory()->create());

        $hotel = Hotel::factory()->create();
        $path = tempnam(sys_get_temp_dir(), 'inventory');

        file_put_contents($path, "category,menu_type,name,description,price,people_count\n");

        $file = new UploadedFile($path, 'inventory.csv', 'application/vnd.ms-excel', null, true);

        $this->post(route('admin.hotels.inventories.import', $hotel), [
            'inventory_file' => $file,
        ])
            ->assertRedirect(route('admin.hotels.inventories', $hotel))
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status', '0 inventory items imported.');
    }

    public function test_admin_can_import_csv_with_missing_or_extra_columns(): void
    {
        $this->actingAs(User::factory()->create());

        $hotel = Hotel::factory()->create();
        $file = UploadedFile::fake()->createWithContent(
            'inventory.csv',
            "\xEF\xBB\xBFcategory,menu_type,name,description,price,people_count\nFoods,Breakfast,String hoppers\nEntertainment,Pool,Swimming Pool,Evening pool pass,2500,2,extra value\n"
        );

        $response = $this->post(route('admin.hotels.inventories.import', $hotel), [
            'inventory_file' => $file,
        ]);

        $response
            ->assertRedirect(route('admin.hotels.inventories', $hotel))
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status', '2 inventory items imported.');

        $this->assertDatabaseHas('hotel_inventories', [
            'hotel_id' => $hotel->id,
            'category' => 'Foods',
            'menu_type' => 'Breakfast',
            'name' => 'String hoppers',
        ]);

        $this->assertDatabaseHas('hotel_inventories', [
            'hotel_id' => $hotel->id,
            'category' => 'Entertainment',
            'menu_type' => 'Pool',
            'name' => 'Swimming Pool',
            'price' => 2500,
            'people_count' => 2,
        ]);
    }

    public function test_admin_can_export_inventory_to_csv(): void
    {
        $this->actingAs(User::factory()->create());

        $hotel = Hotel::factory()->create(['name' => 'Galle Export Stay']);
        HotelInventory::factory()->for($hotel)->create([
            'category' => 'Foods',
            'menu_type' => 'Dinner',
            'name' => 'Seafood dinner',
            'description' => 'Fresh fish curry with rice.',
            'price' => 4200,
            'people_count' => 2,
        ]);
        HotelInventory::factory()->for($hotel)->create([
            'category' => 'Entertainment',
            'menu_type' => 'Games',
            'name' => 'Indoor games',
            'description' => 'Carrom and chess.',
            'price' => 800,
            'people_count' => 4,
        ]);

        $response = $this->get(route('admin.hotels.inventories.export', $hotel));

        $response
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $content = $response->streamedContent();

        $this->assertStringContainsString('category,menu_type,name,description,price,people_count', $content);
        $this->assertStringContainsString('Foods,Dinner,"Seafood dinner","Fresh fish curry with rice.",4200.00,2', $content);
        $this->assertStringContainsString('Entertainment,Games,"Indoor games","Carrom and chess.",800.00,4', $content);
    }

    public function test_admin_can_open_inventory_edit_form(): void
    {
        $this->actingAs(User::factory()->create());

        $hotel = Hotel::factory()->create();
        $inventory = HotelInventory::factory()->for($hotel)->create([
            'name' => 'Dinner buffet',
        ]);

        $this->get(route('admin.hotels.inventories.edit', [$hotel, $inventory]))
            ->assertOk()
            ->assertSee('Edit inventory')
            ->assertSee('Dinner buffet');
    }

    public function test_admin_can_update_inventory(): void
    {
        $this->actingAs(User::factory()->create());

        $hotel = Hotel::factory()->create();
        $inventory = HotelInventory::factory()->for($hotel)->create([
            'name' => 'Old meal',
        ]);

        $response = $this->put(route('admin.hotels.inventories.update', [$hotel, $inventory]), [
            'category' => 'Entertainment',
            'menu_type' => 'Games',
            'name' => 'Indoor games package',
            'description' => 'Carrom, chess, and cards for a relaxed evening.',
            'price' => 1200,
            'people_count' => 4,
        ]);

        $response
            ->assertRedirect(route('admin.hotels.inventories', $hotel))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('hotel_inventories', [
            'id' => $inventory->id,
            'category' => 'Entertainment',
            'menu_type' => 'Games',
            'name' => 'Indoor games package',
            'price' => 1200,
            'people_count' => 4,
        ]);
    }

    public function test_admin_can_delete_inventory(): void
    {
        $this->actingAs(User::factory()->create());

        $hotel = Hotel::factory()->create();
        $inventory = HotelInventory::factory()->for($hotel)->create();

        $response = $this->delete(route('admin.hotels.inventories.destroy', [$hotel, $inventory]));

        $response
            ->assertRedirect(route('admin.hotels.inventories', $hotel))
            ->assertSessionHas('status');

        $this->assertDatabaseMissing('hotel_inventories', [
            'id' => $inventory->id,
        ]);
    }

    public function test_admin_cannot_update_inventory_from_another_hotel(): void
    {
        $this->actingAs(User::factory()->create());

        $hotel = Hotel::factory()->create();
        $otherHotel = Hotel::factory()->create();
        $inventory = HotelInventory::factory()->for($otherHotel)->create();

        $this->put(route('admin.hotels.inventories.update', [$hotel, $inventory]), [
            'category' => 'Foods',
            'name' => 'Wrong hotel item',
        ])->assertNotFound();
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
