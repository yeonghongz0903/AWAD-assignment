@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Contact Us') }}</div>

                <div class="card-body">
                    <div class="mb-4">
                        <p class="lead">We'd love to hear from you! Whether you have questions about our Chiikawa toys, need help with an order, or just want to share your love for Chiikawa Bear, we're here to assist you.</p>
                    </div>

                    <div class="mb-4">
                        <h3>Contact Information</h3>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <strong>Email:</strong> <a href="mailto:support@lottoshop.com">support@lottoshop.com</a>
                            </li>
                            <li class="list-group-item">
                                <strong>Phone:</strong> +603 91889888
                            </li>
                            <li class="list-group-item">
                                <strong>Address:</strong> No32-1A, Lingkaran Sunway Velocity, Sunway Velocity, 55100 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur
                            </li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h3>Operating Hours</h3>
                        <ul class="list-group">
                            <li class="list-group-item"><strong>Mon - Fri:</strong> 9 AM - 6 PM</li>
                            <li class="list-group-item"><strong>Sat:</strong> 9 AM - 3 PM</li>
                            <li class="list-group-item"><strong>Sun:</strong> Closed</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <p>For quick inquiries, feel free to fill out the contact form below, and we'll get back to you as soon as possible. Thank you for choosing Chiikawa Shop as your go-to source for all things Chiikawa!</p>
                    </div>

                    <div class="card">
                        <div class="card-header">Contact Form</div>
                        <div class="card-body">
                            <form>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subject</label>
                                    <input type="text" class="form-control" id="subject" required>
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control" id="message" rows="5" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Send Message</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 