@extends('layout')

@section('content')
        @if(!$mobilebrowser)
<div class="parallax-window jumbotron_holder" data-parallax="scroll" data-speed="0.15">
            <div class="parallax-slider">
                <div id="particles-js" data-aos="fade-in"></div>
            </div>
            <div class="jumbotron_anchor">
                <div class="jumbotron_card col-xs-12">
                    <h1 class="line-1 anim-typewriter" data-aos="fade-up">Hi, I'm <span>Tom</span>!</h1>
                    <h2 class="line-2 anim-typewriter2" data-aos="fade-up" data-aos-delay="2300">Welcome to my website.</h2>
                    <h4 class="message" data-aos="fade-up" data-aos-delay="2300" style="transition-delay: 5.2s;">It's currently under construction.</h4>
                    <h4 class="message" data-aos="fade-up" data-aos-delay="2300" style="transition-delay: 6s;">(Please come back later.)</h4>
                    <div class="btn_holder">
                        <div class="git_btn_holder" data-aos="fade-up" data-aos-delay="2300" style="transition-delay: 7s;"><a class="btn btn-primary git_btn" href="https://www.github.com/tomvdbroecke" target="_blank"><i class="fab fa-github"></i> Github</a></div>
                        <div class="login_btn_holder" data-aos="fade-up" data-aos-delay="2300" style="transition-delay: 8s;"><a class="btn btn-primary git_btn" href="/login"><i class="fas fa-user-check"></i> Client Login</a></div>
                    </div>
                </div>
            </div>
        </div>
        @else
<div class="jumbotron_holder">
            <div class="parallax-slider">
                <div id="particles-js" class="particles-js-mobile" data-aos="fade-in"></div>
                <div class="jumbotron_holder_mobile">
                    <div class="jumbotron_anchor">
                        <div class="jumbotron_card col-xs-12">
                            <h1 class="line-1 anim-typewriter" data-aos="fade-up" data-aos-anchor-placement="top-bottom">Hi, I'm <span>Tom</span>!</h1>
                            <h2 class="line-2 anim-typewriter2" data-aos="fade-up" data-aos-delay="2300" data-aos-anchor-placement="top-bottom">Welcome to my website.</h2>
                            <h4 class="message" data-aos="fade-up" data-aos-delay="2300" style="transition-delay: 5.2s;" data-aos-anchor-placement="top-bottom">It's currently under construction.</h4>
                            <h4 class="message" data-aos="fade-up" data-aos-delay="2300" style="transition-delay: 6s;" data-aos-anchor-placement="top-bottom">(Please come back later.)</h4>
                            <div class="btn_holder">
                                <div class="git_btn_holder" data-aos="fade-up" data-aos-delay="2300" style="transition-delay: 7s;"><a class="btn btn-primary git_btn" href="https://www.github.com/tomvdbroecke" target="_blank"><i class="fab fa-github"></i> Github</a></div>
                                <div class="login_btn_holder" data-aos="fade-up" data-aos-delay="2300" style="transition-delay: 8s;"><a class="btn btn-primary git_btn" href="/login"><i class="fas fa-user-check"></i> Client Login</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
@endsection