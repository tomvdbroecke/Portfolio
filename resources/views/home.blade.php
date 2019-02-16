@extends('layout')

@section('content')
        <div class="parallax-window jumbotron_holder" data-parallax="scroll" data-speed="0.15">
            <div class="parallax-slider">
                <div id="particles-js" data-aos="fade-in"></div>
                <!--<img src="https://images.pexels.com/photos/257840/pexels-photo-257840.jpeg" style="width: 100%;">-->
            </div>
            <div class="jumbotron_anchor">
                <div class="jumbotron_card col-xs-12">
                    <h1 class="line-1 anim-typewriter" data-aos="fade-up">Hi, I'm <span>Tom</span>!</h1>
                    <h2 class="line-2 anim-typewriter2" data-aos="fade-up" data-aos-delay="2300">Welcome to my website.</h2>
                    <h4 class="message" data-aos="fade-up" style="transition-delay: 5.2s;">It's currently under construction.</h4>
                    <h4 class="message" data-aos="fade-up" style="transition-delay: 6s;">(Please come back later.)</h4>
                </div>
            </div>
        </div>
@endsection