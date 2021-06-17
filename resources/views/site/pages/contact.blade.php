@extends('layouts.pages.contact')
@section('title')
Contact Us
@endsection
@section('content')

<!-- Breadcrumb Section Begin -->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <a href="{{ route('home') }}"><i class="fa fa-home"></i> Home</a>
                    <span>Contact</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Section Begin -->

<!-- Map Section Begin -->
<div class="map spad">
    <div class="container">
        <div class="map-inner">
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15865.894575663993!2d7.0281902!3d6.201075!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x7ed90cdacc384b13!2sOGENE%2098.3FM!5e0!3m2!1sen!2sng!4v1580815480921!5m2!1sen!2sng" height="610" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
            {{-- <div class="icon">
                <i class="fa fa-map-marker"></i>
            </div> --}}
        </div>
    </div>
</div>
<!-- Map Section Begin -->

<!-- Contact Section Begin -->
<section class="contact-section spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-5">
                <div class="contact-title">
                    <h4>Contacts Us</h4>
                    <p>
                        Best indigenous Radio Station, South-East of the Niger. Infusing Local content, reaching out to upwardly mobile and locals. Bridging the gap between the urban contemporary and Indigenous broadcasting. If you ever find yourself in Anambra state, make sure you pick up OGENE.
                        <br>Visit or call us
                    </p>
                </div>
                <div class="contact-widget">
                    <div class="cw-item">
                        <div class="ci-icon">
                            <i class="ti-location-pin"></i>
                        </div>
                        <div class="ci-text">
                            <span>Address:</span>
                            <p>KM 80 Enugu/Onitsha Expressway, Awka, Anambra State, Nigeria</p>
                        </div>
                    </div>
                    <div class="cw-item">
                        <div class="ci-icon">
                            <i class="ti-mobile"></i>
                        </div>
                        <div class="ci-text">
                            <span>Phone:</span>
                            <p>+234 807 772 6636, +234 816 749 4172</p>
                        </div>
                    </div>
                    <div class="cw-item">
                        <div class="ci-icon">
                            <i class="ti-email"></i>
                        </div>
                        <div class="ci-text">
                            <span>Email:</span>
                            <p>info@ogene983fm.com, ogenefm983@gmail.com</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 offset-lg-1">
                <div class="contact-form">
                    <div class="leave-comment">
                        <h4>Leave A Message</h4>
                        <p>Our staff will call back later and answer your questions.</p>
                        <form action="{{ route('contact.store') }}" class="comment-form" method="POST">
                            <div class="row">
                                <div class="col-lg-6">
                                    <input type="text" placeholder="Your name" name="name">
                                </div>
                                <div class="col-lg-6">
                                    <input type="email" placeholder="Your email" name="email">
                                </div>
                                <div class="col-lg-12">
                                    <input type="text" placeholder="Your subject" name="subject">
                                </div>
                                <div class="col-lg-12">
                                    <textarea placeholder="Your message" name="message"></textarea>
                                    <button type="submit" class="site-btn">Send message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Contact Section End -->
@endsection
