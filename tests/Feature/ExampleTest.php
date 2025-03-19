<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Carbon\Carbon;
use App\Models\User;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected $user, $bearer_token;

    public function setUp():void
    {
        parent::setUp();

        $this->artisan('db:seed');

        // $this->user = factory(User::class)->create();

        // $this->actingAs($this->user, 'api');
    }
    
    /**
     * A basic test example - sign up user.
     *
     * @return void
     */
    public function testCanSignupUser()
    {
        $formData = [
            'name'=>'Keith Jordan',   
            'email'=>'shadow902@gmail.com',
            'password'=> 'Welc0me!'
        ];

        // $this->withoutExceptionHandling();

        $response = $this->post('/api/auth/signup', $formData)
            ->assertStatus(200)
            ->assertJsonStructure(['access_token', 'token_type', 'expires_in']);

        // Access the whole JSON response
        $data = $response->json();
        // Destructure specific values
        $this->bearer_token = $data['access_token'];


    }
    
    public function testCanLoginUser()
    {
        $user = User::find(1);

        $formData = [               
            'email'=> $user->email,
            'password'=> 'Welc0me!'
        ];

        // $this->withoutExceptionHandling();

        $response = $this->post('/api/auth/login', $formData)
            ->assertStatus(200);

        // Access the whole JSON response
        $data = $response->json();
        // Destructure specific values
        $this->bearer_token = $data['access_token'];
    }

    public function testSuperAdminCanGetAllUsers()
    {
        // Get SuperAdmin user with bearer token
        $user = User::find(1);

        $formData = [               
            'email'=> $user->email,
            'password'=> 'Welc0me!'
        ];

        // $this->withoutExceptionHandling();

        $response = $this->post('/api/auth/login', $formData)
            ->assertStatus(200);

        // Access the whole JSON response
        $data = $response->json();
        // Destructure specific values
        $this->bearer_token = $data['access_token'];

        // Call SupreAdmin get all users
        $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $this->bearer_token,
            ])
            ->get('/api/admin/users')
            ->assertStatus(200)
            ->assertJsonCount(23,'data')
            ->assertJsonStructure([
                'data' => [
                            'id',
                            'name',
                            'email',
                            'created_at',
                            'updated_at',
            //                 'profile' => [
            //                     'id',
            //                     'first_name',
            //                     'last_name',
            //                     'address1',
            //                     'address2',
            //                     'city',
            //                     'state',
            //                     'zip_code',
            //                     'phone_number',
            //                     'phone_type',
            //                     'dob',
            //                     'queversary'
            //                 ],
            //             ]
                    ]
            ]);    

    }
}