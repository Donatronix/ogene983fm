<!-- Footer Section Begin -->
<footer class="footer-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="footer-left">
                    <div class="footer-logo" style="background-color: white; padding: 15px;">
                        <a href="#"><img src="{{ asset('images/logo.png') }}" alt=""></a>
                    </div>
                    <ul>
                        <li>Address: KM 80 Enugu/Onitsha Expressway, Awka, Anambra State, Nigeria</li>
                        <li>Phone: +234 807 772 6636,<br/> +234 816 749 4172</li>
                        <li>Email: info@ogene983fm.com, ogenefm983@gmail.com</li>
                    </ul>
                    <div class="footer-social">
                        <a href="https://www.facebook.com/ogene983fm"><i class="fa fa-facebook"></i></a>
                        <a href="https://twitter.com/ogenefm983"><i class="fa fa-twitter"></i></a>
                        <a href="https://instagram.com/ogene983fm"><i class="fa fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 text-center">
                <div class="footer-widget">
                    <h5>Quick Links</h5>
                    <ul>
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="#">Metro</a></li>
                        <li><a href="{{ route('post.index') }}">Blog</a></li>
                        <li><a href="{{ route('programme.index') }}">Programmes</a></li>
                        <li><a href="{{ route('presenter.index') }}">On Air Personalities</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="newslatter-item">
                    <h5>Join Our Newsletter Now</h5>
                    <p>Get E-mail updates about latest happenings.</p>
                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="subscribe-form">
                        @csrf
                        <input type="text" placeholder="Enter Your Mail" name="email" required>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <button type="submit">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright-reserved">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="copyright-text">
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        Copyright &copy;<script>
                            document.write(new Date().getFullYear());

                        </script> All rights reserved | Ogene98.3FM
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    </div>

                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Footer Section End -->

<!-- Js Plugins -->
<script src="{{ asset('frontend/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('frontend/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('frontend/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('frontend/js/jquery.countdown.min.js') }}"></script>
<script src="{{ asset('frontend/js/jquery.nice-select.min.js') }}"></script>
<script src="{{ asset('frontend/js/jquery.zoom.min.js') }}"></script>
<script src="{{ asset('frontend/js/jquery.dd.min.js') }}"></script>
<script src="{{ asset('frontend/js/jquery.slicknav.js') }}"></script>
<script src="{{ asset('frontend/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('frontend/js/main.js') }}"></script>
@stack('js')
