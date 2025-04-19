@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('About Us') }}</div>

                <div class="card-body">
                    <section class="mb-4">
                        <h2>Our Goals</h2>
                        <p>We are committed to delivering the highest quality of services to our customers by consistently striving for excellence, understanding their needs, and exceeding their expectations. Our goal is to build lasting relationships based on trust, reliability, and outstanding customer satisfaction.</p>
                    </section>

                    <section class="mb-4">
                        <h2>Our Team</h2>
                        <p>We are a group of dedicated professionals who are deeply committed to excellence in everything we do. With a shared passion for delivering high-quality results, we continuously strive to improve, innovate, and uphold the highest standards in our work. Our team combines expertise, integrity, and collaboration to ensure that we not only meet but exceed expectations in every project we undertake.</p>
                    </section>

                    <section class="mb-4">
                        <h2>Our Values</h2>
                        <p>We believe in integrity, transparency, and accountability. Our values guide our actions and decisions, ensuring that we always act in the best interest of our customers and stakeholders.</p>
                    </section>

                    <section class="mb-4">
                        <h2>Important Notes:</h2>
                        <ul class="list-group">
                            <li class="list-group-item">Adding a product to the cart does not guarantee that the product is reserved. The product is reserved at the same time as the order procedure is completed.</li>
                            <li class="list-group-item">You cannot place an order that combines Pre-orders start and regular products.</li>
                            <li class="list-group-item">Changes or cancellations cannot be accepted after an order is placed. Please place your order after agreeing to this.</li>
                            <li class="list-group-item">If you do not receive the order confirmation email, there may be a possibility of receiving it in the spam folder, incorrect email settings by the customer, or incorrect email address entry. Please check and contact us from the Inquiry form.</li>
                            <li class="list-group-item">We do not accept gift wrapping.</li>
                            <li class="list-group-item">Please make sure there are no mistakes in the input of the delivery destination information before completing your order.</li>
                            <li class="list-group-item">We cannot handle address changes (redelivery) after shipping from our store, so please contact Yamato Transport yourself.</li>
                            <li class="list-group-item">We cannot guarantee any additional shipping charges incurred due to address changes (redelivery).</li>
                            <li class="list-group-item">When ordering Plush or Mascot products, please be sure to check the quality standards posted on this page. At our store, these cases are set as quality standards, so we ask that you place your order after understanding in advance.</li>
                            <li class="list-group-item">Orders exceeding the limited quantity will be canceled. Please purchase according to the limited quantity.</li>
                        </ul>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 