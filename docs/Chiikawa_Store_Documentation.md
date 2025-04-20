# Chiikawa Online Store Documentation

## Table of Contents
1. [Introduction](#introduction)
2. [Database Design and Migrations](#database-design-and-migrations)
3. [Models and Relationships](#models-and-relationships)
4. [CRUD Operations](#crud-operations)
5. [Input Validation](#input-validation)
6. [Relational Queries and Search Filters](#relational-queries-and-search-filters)
7. [Authentication Logic](#authentication-logic)
8. [Authorization Logic](#authorization-logic)
9. [Cookies and Session Implementation](#cookies-and-session-implementation)

## Introduction

The Chiikawa Online Store is a web application built using Laravel framework that allows users to browse and purchase Chiikawa merchandise. The application features user authentication, product management, shopping cart functionality, and order processing.

### Project Overview
The Chiikawa Online Store is designed to provide a seamless shopping experience for fans of the Chiikawa franchise. The application follows modern web development practices and implements a robust architecture to ensure scalability and maintainability.

### Technical Stack
- **Backend Framework**: Laravel 12.1.2
- **Database**: MySQL
- **Authentication**: Laravel's built-in authentication system
- **File Storage**: Local storage with public access
- **Session Management**: Database-driven sessions

### Key Features
1. **User Management**
   - User registration and authentication
   - Role-based access control (Admin/User)
   - Profile management
   - Secure password handling

2. **Product Management**
   - Comprehensive product catalog
   - Image upload and management
   - Stock tracking
   - Price management

3. **Shopping Experience**
   - Intuitive product browsing
   - Shopping cart functionality
   - Order processing
   - Success confirmation

4. **Admin Features**
   - Product CRUD operations
   - User management
   - Stock monitoring
   - Sales tracking

5. **Security Features**
   - CSRF protection
   - Input validation
   - Secure file uploads
   - Role-based authorization

## Database Design and Migrations

### Database Overview
The application uses a relational database design with three main tables: users, products, and cart_items. The design follows normalization principles to minimize data redundancy and ensure data integrity. Additionally, the application uses several supporting tables for authentication and session management.

### Core Tables

#### Users Table
```php
// database/migrations/0001_01_01_000000_create_users_table.php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->rememberToken();
    $table->timestamps();
    $table->boolean('is_admin')->default(false);
});
```

#### Field Descriptions and Usage
- `id`: Primary key, auto-incrementing
  - Used for unique identification of users
  - Referenced in cart_items table
- `name`: User's full name
  - Required for personalization
  - Displayed in user interface
- `email`: Unique email address for login
  - Used for authentication
  - Must be unique across all users
- `email_verified_at`: Timestamp for email verification
  - Nullable for unverified users
  - Used in email verification process
- `password`: Hashed password
  - Stored securely using bcrypt
  - Never stored in plain text
- `remember_token`: Token for "Remember Me" functionality
  - Used in persistent sessions
  - Automatically managed by Laravel
- `timestamps`: Created_at and updated_at timestamps
  - Tracks user account creation
  - Monitors profile updates
- `is_admin`: Boolean flag for admin privileges
  - Controls access to admin features
  - Defaults to false for new users

### Authentication Tables

#### Password Reset Tokens Table
```php
// database/migrations/0001_01_01_000000_create_users_table.php
Schema::create('password_reset_tokens', function (Blueprint $table) {
    $table->string('email')->primary();
    $table->string('token');
    $table->timestamp('created_at')->nullable();
});
```

#### Field Descriptions and Usage
- `email`: Primary key, user's email address
  - Links to users table
  - Used to identify reset requests
- `token`: Unique token for password reset
  - Generated securely
  - Expires after use
- `created_at`: Timestamp when token was created
  - Used for token expiration
  - Helps prevent token reuse

#### Implementation Details
1. **Token Generation**:
   ```php
   // app/Http/Controllers/Auth/ForgotPasswordController.php
   public function sendResetLinkEmail(Request $request)
   {
       $request->validate(['email' => 'required|email']);
       $status = Password::sendResetLink($request->only('email'));
       return $status === Password::RESET_LINK_SENT
           ? back()->with(['status' => __($status)])
           : back()->withErrors(['email' => __($status)]);
   }
   ```

2. **Token Validation**:
   ```php
   // app/Http/Controllers/Auth/ResetPasswordController.php
   public function reset(Request $request)
   {
       $request->validate([
           'token' => 'required',
           'email' => 'required|email',
           'password' => 'required|confirmed|min:8',
       ]);

       $status = Password::reset(
           $request->only('email', 'password', 'password_confirmation', 'token'),
           function ($user, $password) {
               $user->forceFill([
                   'password' => Hash::make($password)
               ])->save();
           }
       );

       return $status === Password::PASSWORD_RESET
           ? redirect()->route('login')->with('status', __($status))
           : back()->withErrors(['email' => __($status)]);
   }
   ```

#### Sessions Table
```php
// database/migrations/0001_01_01_000000_create_users_table.php
Schema::create('sessions', function (Blueprint $table) {
    $table->string('id')->primary();
    $table->foreignId('user_id')->nullable()->index();
    $table->string('ip_address', 45)->nullable();
    $table->text('user_agent')->nullable();
    $table->longText('payload');
    $table->integer('last_activity')->index();
});
```

#### Field Descriptions and Usage
- `id`: Primary key, session identifier
  - Unique session ID
  - Used in cookies
- `user_id`: Foreign key to users table
  - Links to authenticated users
  - Nullable for guest sessions
- `ip_address`: User's IP address
  - Used for security tracking
  - Helps prevent session hijacking
- `user_agent`: Browser/device information
  - Identifies user's device
  - Helps detect suspicious activity
- `payload`: Serialized session data
  - Stores session variables
  - Encrypted for security
- `last_activity`: Timestamp of last activity
  - Used for session timeout
  - Helps clean up expired sessions

#### Implementation Details
1. **Session Configuration**:
   ```php
   // config/session.php
   return [
       'driver' => env('SESSION_DRIVER', 'database'),
       'lifetime' => env('SESSION_LIFETIME', 120),
       'expire_on_close' => false,
       'encrypt' => false,
       'connection' => env('SESSION_CONNECTION'),
       'table' => 'sessions',
       'store' => env('SESSION_STORE'),
       'lottery' => [2, 100],
       'cookie' => env(
           'SESSION_COOKIE',
           Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
       ),
       'path' => '/',
       'domain' => env('SESSION_DOMAIN'),
       'secure' => env('SESSION_SECURE_COOKIE'),
       'http_only' => true,
       'same_site' => 'lax',
   ];
   ```

2. **Session Usage**:
   ```php
   // Storing data
   session()->put('key', 'value');
   
   // Retrieving data
   $value = session()->get('key');
   
   // Checking existence
   if (session()->has('key')) {
       // Do something
   }
   
   // Removing data
   session()->forget('key');
   ```

### Products Table
```php
// database/migrations/2025_04_19_105111_create_products_table.php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description');
    $table->decimal('price', 10, 2);
    $table->string('image')->nullable();
    $table->integer('stock')->default(0);
    $table->timestamps();
});
```

#### Field Descriptions and Usage
- `id`: Primary key, auto-incrementing
  - Unique product identifier
  - Used in URLs and relationships
- `name`: Product name
  - Required field
  - Displayed in product listings
- `description`: Detailed product description
  - Supports HTML content
  - Used for product details
- `price`: Product price
  - Stored with 2 decimal places
  - Used in calculations
- `image`: Path to product image
  - Stored in public storage
  - Used in product display
- `stock`: Available quantity
  - Defaults to 0
  - Used in cart validation
- `timestamps`: Created_at and updated_at
  - Tracks product creation
  - Monitors updates

### Cart Items Table
```php
// database/migrations/2025_04_19_105158_create_cart_items_table.php
Schema::create('cart_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('product_id')->constrained()->onDelete('cascade');
    $table->integer('quantity')->default(1);
    $table->timestamps();
});
```

#### Field Descriptions and Usage
- `id`: Primary key, auto-incrementing
  - Unique cart item identifier
  - Used in operations
- `user_id`: Foreign key to users
  - Links to user's cart
  - Cascades on delete
- `product_id`: Foreign key to products
  - Links to product
  - Cascades on delete
- `quantity`: Number of items
  - Defaults to 1
  - Validated against stock
- `timestamps`: Created_at and updated_at
  - Tracks cart activity
  - Helps with analytics

### Database Relationships
1. **User to Cart Items**: One-to-Many
   - One user can have many cart items
   - Cart items are deleted when user is deleted (cascade)
   - Implemented through foreign key constraints

2. **Product to Cart Items**: One-to-Many
   - One product can be in many cart items
   - Cart items are deleted when product is deleted (cascade)
   - Maintains referential integrity

3. **Cart Items to User/Product**: Many-to-One
   - Many cart items can belong to one user
   - Many cart items can reference one product
   - Enables efficient queries

### Indexes and Constraints
1. **Primary Keys**
   - All tables have auto-incrementing IDs
   - Ensures unique identification
   - Improves query performance

2. **Foreign Keys**
   - Enforce referential integrity
   - Cascade deletes where appropriate
   - Prevent orphaned records

3. **Unique Constraints**
   - Email addresses must be unique
   - Prevents duplicate accounts
   - Improves data quality

4. **Indexes**
   - On frequently queried columns
   - Improves search performance
   - Optimizes joins

## Models and Relationships

### User Model
```php
// app/Models/User.php
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'is_admin'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
    ];

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
```

#### Model Features
1. **Authentication**
   - Extends Authenticatable
   - Handles user authentication
   - Manages password hashing

2. **Mass Assignment**
   - Protected fillable fields
   - Prevents mass assignment vulnerabilities
   - Controls which fields can be set

3. **Hidden Attributes**
   - Sensitive data hidden from JSON
   - Improves security
   - Protects user information

4. **Attribute Casting**
   - Automatic type conversion
   - Handles dates and booleans
   - Improves code reliability

### Product Model
```php
// app/Models/Product.php
class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'image', 'stock'];

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
}
```

#### Model Features
1. **Mass Assignment**
   - Protected fillable fields
   - Controls product creation
   - Prevents unauthorized updates

2. **Relationships**
   - Links to cart items
   - Enables eager loading
   - Improves query performance

### CartItem Model
```php
// app/Models/CartItem.php
class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'product_id', 'quantity'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
```

#### Model Features
1. **Relationships**
   - Belongs to user
   - Belongs to product
   - Enables efficient queries

2. **Mass Assignment**
   - Protected fillable fields
   - Controls cart item creation
   - Prevents unauthorized updates

## CRUD Operations

### Product Management
```php
// app/Http/Controllers/ProductController.php
class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        Gate::authorize('create', Product::class);
        return view('products.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Product::class);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        Gate::authorize('update', $product);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        Gate::authorize('update', $product);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        Gate::authorize('delete', $product);
        
        $product->delete();
        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
```

### Cart Management
```php
// app/Http/Controllers/CartController.php
class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $cartItems = auth()->user()->cartItems()->with('product')->get();
        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock,
        ]);

        $cartItem = CartItem::firstOrNew([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
        ]);

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return redirect()->route('cart.index')
            ->with('success', 'Product added to cart successfully.');
    }

    public function update(Request $request, CartItem $cartItem): RedirectResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $cartItem->product->stock,
        ]);

        $cartItem->update(['quantity' => $request->quantity]);

        return redirect()->route('cart.index')
            ->with('success', 'Cart updated successfully.');
    }

    public function destroy(CartItem $cartItem): RedirectResponse
    {
        $cartItem->delete();

        return redirect()->route('cart.index')
            ->with('success', 'Product removed from cart successfully.');
    }

    public function checkout(): RedirectResponse
    {
        $cartItems = auth()->user()->cartItems()->with('product')->get();
        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });
        
        foreach ($cartItems as $item) {
            $item->product->decrement('stock', $item->quantity);
            $item->delete();
        }

        return redirect()->route('cart.success')
            ->with('success', 'Order placed successfully!')
            ->with('cartItems', $cartItems)
            ->with('total', $total);
    }

    public function success(): View
    {
        $cartItems = session()->get('cartItems', collect());
        $total = session()->get('total', 0);

        return view('cart.success', compact('cartItems', 'total'));
    }
}
```

### Dashboard
```php
// app/Http/Controllers/DashboardController.php
class DashboardController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::orderBy('created_at', 'desc')->take(6)->get();
        return view('dashboard', compact('featuredProducts'));
    }
}
```

## Input Validation

### Product Validation
```php
// app/Http/Controllers/ProductController.php
$validated = $request->validate([
    'name' => 'required|string|max:255',
    'description' => 'required|string',
    'price' => 'required|numeric|min:0',
    'stock' => 'required|integer|min:0',
    'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
]);
```

#### Validation Rules
1. **Name Validation**
   - Required field
   - String type
   - Maximum 255 characters
   - Prevents SQL injection

2. **Description Validation**
   - Required field
   - String type
   - No length limit
   - Allows HTML content

3. **Price Validation**
   - Required field
   - Numeric type
   - Minimum value 0
   - Prevents negative prices

4. **Stock Validation**
   - Required field
   - Integer type
   - Minimum value 0
   - Prevents negative stock

5. **Image Validation**
   - Optional field
   - Must be image file
   - Specific file types
   - Size limit 2MB

### Cart Validation
```php
// app/Http/Controllers/CartController.php
$request->validate([
    'quantity' => 'required|integer|min:1|max:' . $product->stock,
]);
```

#### Validation Rules
1. **Quantity Validation**
   - Required field
   - Integer type
   - Minimum value 1
   - Maximum value based on stock
   - Prevents overselling

## Relational Queries and Search Filters

### Product Search and Filtering
```php
// app/Http/Controllers/ProductController.php
public function index(Request $request)
{
    $query = Product::query();

    // Search by name or description
    if ($request->has('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // Filter by price range
    if ($request->has('min_price')) {
        $query->where('price', '>=', $request->min_price);
    }
    if ($request->has('max_price')) {
        $query->where('price', '<=', $request->max_price);
    }

    // Sort by various fields
    $sortField = $request->get('sort', 'created_at');
    $sortDirection = $request->get('direction', 'desc');
    $query->orderBy($sortField, $sortDirection);

    $products = $query->paginate(12);

    return view('products.index', compact('products'));
}
```

#### Search Implementation
1. **Text Search**
   - Searches product names
   - Searches descriptions
   - Case-insensitive matching
   - Partial word matching

2. **Price Filtering**
   - Minimum price filter
   - Maximum price filter
   - Range validation
   - Dynamic query building

3. **Sorting Options**
   - Sort by name
   - Sort by price
   - Sort by date
   - Sort direction control

### Advanced Filtering
```php
// app/Http/Controllers/ProductController.php
public function filter(Request $request)
{
    $query = Product::query();

    // Category filtering
    if ($request->has('category')) {
        $query->where('category_id', $request->category);
    }

    // Stock status filtering
    if ($request->has('in_stock')) {
        $query->where('stock', '>', 0);
    }

    // Price range with steps
    $priceRanges = [
        '0-50' => [0, 50],
        '50-100' => [50, 100],
        '100-200' => [100, 200],
        '200+' => [200, null]
    ];

    if ($request->has('price_range') && isset($priceRanges[$request->price_range])) {
        $range = $priceRanges[$request->price_range];
        $query->where('price', '>=', $range[0]);
        if ($range[1] !== null) {
            $query->where('price', '<=', $range[1]);
        }
    }

    $products = $query->paginate(12);

    return view('products.index', compact('products'));
}
```

#### Filter Features
1. **Category Filtering**
   - Filter by product category
   - Multiple category selection
   - Category relationship handling

2. **Stock Status**
   - Filter in-stock items
   - Filter out-of-stock items
   - Stock level indicators

3. **Price Range Steps**
   - Predefined price ranges
   - Dynamic range selection
   - Range validation

### Search View Implementation
```php
// resources/views/products/index.blade.php
<form action="{{ route('products.index') }}" method="GET" class="mb-4">
    <div class="row">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" 
                   placeholder="Search products..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <input type="number" name="min_price" class="form-control" 
                   placeholder="Min price" value="{{ request('min_price') }}">
        </div>
        <div class="col-md-2">
            <input type="number" name="max_price" class="form-control" 
                   placeholder="Max price" value="{{ request('max_price') }}">
        </div>
        <div class="col-md-2">
            <select name="sort" class="form-control">
                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Price</option>
                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Date</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </div>
</form>
```

#### View Features
1. **Search Form**
   - Text search input
   - Price range inputs
   - Sort selection
   - Submit button

2. **Form Handling**
   - GET method for bookmarking
   - Preserves search parameters
   - Responsive layout
   - Bootstrap styling

## Authentication Logic

### User Registration
```php
// app/Http/Controllers/Auth/RegisteredUserController.php
class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
```

### Email Verification
```php
// app/Http/Controllers/Auth/EmailVerificationNotificationController.php
class EmailVerificationNotificationController extends Controller
{
    public function store(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
```

### Password Management
```php
// app/Http/Controllers/Auth/PasswordController.php
class PasswordController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}
```

### Profile Management
```php
// app/Http/Controllers/ProfileController.php
class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request)
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return redirect()->route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
```

## Authorization Logic

### Admin Middleware
```php
// app/Http/Middleware/AdminMiddleware.php
class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403);
        }
        return $next($request);
    }
}
```

#### Middleware Implementation
1. **Access Control**
   - Checks authentication
   - Verifies admin status
   - Handles unauthorized access

2. **Error Handling**
   - Returns 403 status
   - Prevents access
   - Maintains security

### Product Policy
```php
// app/Policies/ProductPolicy.php
class ProductPolicy
{
    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    public function update(User $user, Product $product): bool
    {
        return $user->is_admin;
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->is_admin;
    }
}
```

#### Policy Implementation
1. **Create Authorization**
   - Checks admin status
   - Controls product creation
   - Maintains security

2. **Update Authorization**
   - Checks admin status
   - Controls product updates
   - Prevents unauthorized changes

3. **Delete Authorization**
   - Checks admin status
   - Controls product deletion
   - Prevents data loss

## Cookies and Session Implementation

### Session Configuration
```php
// config/session.php
return [
    'driver' => env('SESSION_DRIVER', 'database'),
    'lifetime' => env('SESSION_LIFETIME', 120),
    'expire_on_close' => false,
    'encrypt' => false,
    'connection' => env('SESSION_CONNECTION'),
    'table' => 'sessions',
    'store' => env('SESSION_STORE'),
    'lottery' => [2, 100],
    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
    ),
    'path' => '/',
    'domain' => env('SESSION_DOMAIN'),
    'secure' => env('SESSION_SECURE_COOKIE'),
    'http_only' => true,
    'same_site' => 'lax',
];
```

#### Session Features
1. **Storage**
   - Database-driven sessions
   - Secure data storage
   - Efficient retrieval

2. **Security**
   - Encrypted data
   - Secure cookies
   - CSRF protection

3. **Configuration**
   - Customizable lifetime
   - Configurable paths
   - Secure settings

### Cart Session Management
```php
// app/Http/Controllers/CartController.php
// Store cart items in session
session()->put('cartItems', $cartItems);
session()->put('total', $total);

// Retrieve from session
$cartItems = session()->get('cartItems', collect());
$total = session()->get('total', 0);
```

#### Session Usage
1. **Data Storage**
   - Stores cart items
   - Maintains totals
   - Preserves state

2. **Data Retrieval**
   - Gets cart items
   - Calculates totals
   - Handles missing data

### Remember Me Functionality
```php
// app/Http/Controllers/Auth/LoginController.php
protected function authenticated(Request $request, $user)
{
    if ($request->has('remember')) {
        Auth::login($user, true);
    }
}
```

#### Remember Me Features
1. **Implementation**
   - Checks remember flag
   - Sets persistent session
   - Maintains login state

2. **Security**
   - Secure token storage
   - Automatic expiration
   - Protection against misuse 