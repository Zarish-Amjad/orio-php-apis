<!DOCTYPE html>
<html xml:lang="en-US" lang="en-US" xmlns:og="http://opengraphprotocol.org/schema/" >
   <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
      <!-- Latest compiled and minified CSS -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
      <!-- Optional theme -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
      <!-- Latest compiled and minified JavaScript -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script src="http://cdnjs.cloudflare.com/ajax/libs/noUiSlider/8.1.0/nouislider.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
      <style>
         html, body {
         width: 100%;
         height: 100%;
         display: flex;
         align-items: center;
         justify-content: center;
         font-family: 'Nunito', sans-serif;
         color: #000;
         user-select: none;
         }
         #form-wrapper {
         width: 100%;
         display: flex;
         flex-direction: column;
         align-items: center;
         }
         form {
         width: 90%;
         max-width: 500px;
         }
         form #form-title {
         margin-top: 0;
         font-weight: 400;
         text-align: center;
         }
         form #debt-amount-slider {
         display: flex;
         flex-direction: row;
         align-content: stretch;
         position: relative;
         width: 100%;
         height: 50px;
         user-select: none;
         }
         form #debt-amount-slider::before {
         content: " ";
         position: absolute;
         height: 2px;
         width: 100%;
         width: calc(100% * (4 / 5));
         top: 50%;
         left: 50%;
         transform: translate(-50%, -50%);
         background: #000;
         }
         form #debt-amount-slider input, form #debt-amount-slider label {
         box-sizing: border-box;
         flex: 1;
         user-select: none;
         cursor: pointer;
         }
         form #debt-amount-slider label {
         display: inline-block;
         position: relative;
         width: 20%;
         height: 100%;
         user-select: none;
         }
         form #debt-amount-slider label::before {
         content: attr(data-debt-amount);
         position: absolute;
         left: 50%;
         padding-top: 10px;
         transform: translate(-50%, 45px);
         font-size: 14px;
         letter-spacing: 0.4px;
         font-weight: 400;
         white-space: nowrap;
         opacity: 0.85;
         transition: all 0.15s ease-in-out;
         }
         form #debt-amount-slider label::after {
         content: " ";
         position: absolute;
         left: 50%;
         top: 50%;
         transform: translate(-50%, -50%);
         width: 30px;
         height: 30px;
         border: 2px solid #9d9b9b;
         background: #fff;
         border-radius: 50%;
         pointer-events: none;
         user-select: none;
         z-index: 1;
         cursor: pointer;
         transition: all 0.15s ease-in-out;
         }
         form #debt-amount-slider label:hover::after {
         transform: translate(-50%, -50%) scale(1.25);
         }
         form #debt-amount-slider input {
         display: none;
         }
         form #debt-amount-slider input:checked + label::before {
         font-weight: 800;
         opacity: 1;
         }
         form #debt-amount-slider input:checked + label::after {
         border-width: 4px;
         transform: translate(-50%, -50%) scale(0.75);
         }
         form #debt-amount-slider input:checked ~ #debt-amount-pos {
         opacity: 1;
         }
         form #debt-amount-slider input:checked:nth-child(1) ~ #debt-amount-pos {
         left: 7%;
         }
         form #debt-amount-slider input:checked:nth-child(3) ~ #debt-amount-pos {
         left: 21.5%;
         }
         form #debt-amount-slider input:checked:nth-child(5) ~ #debt-amount-pos {
         left: 35.5%;
         }
         form #debt-amount-slider input:checked:nth-child(7) ~ #debt-amount-pos {
         left: 50%;
         }
         form #debt-amount-slider input:checked:nth-child(9) ~ #debt-amount-pos {
         left: 64.5%;
         }
         form #debt-amount-slider input:checked:nth-child(11) ~ #debt-amount-pos {
         left: 78.5%;
         }
         form #debt-amount-slider input:checked:nth-child(13) ~ #debt-amount-pos {
         left: 93%;
         }
         form #debt-amount-slider #debt-amount-pos {
         display: block;
         position: absolute;
         top: 50%;
         width: 12px;
         height: 12px;
         background: #000;
         border-radius: 50%;
         transition: all 0.15s ease-in-out;
         transform: translate(-50%, -50%);
         border: 2px solid #fff;
         opacity: 0;
         z-index: 2;
         }
         form:valid #debt-amount-slider input + label::before {
         transform: translate(-50%, 45px) scale(0.9);
         transition: all 0.15s linear;
         }
         form:valid #debt-amount-slider input:checked + label::before {
         transform: translate(-50%, 45px) scale(1.1);
         transition: all 0.15s linear;
         }
         form + button {
         display: block;
         position: relative;
         margin: 56px auto 0;
         padding: 10px 20px;
         appearance: none;
         transition: all 0.15s ease-in-out;
         font-family: inherit;
         font-size: 24px;
         font-weight: 600;
         background: #fff;
         border: 2px solid #9d9b9b;
         border-radius: 8px;
         outline: 0;
         user-select: none;
         cursor: pointer;
         }
         form + button:hover {
         background: #9d9b9b;
         color: #fff;
         }
         form + button:hover:active {
         transform: scale(0.9);
         }
         form + button:focus {
         background: #4caf50;
         border-color: #4caf50;
         color: #fff;
         pointer-events: none;
         }
         form + button:focus::before {
         animation: spin 1s linear infinite;
         }
         form + button::before {
         display: inline-block;
         width: 0;
         opacity: 0;
         content: "\f3f4";
         font-family: "Font Awesome 5 Pro";
         font-weight: 900;
         margin-right: 0;
         transform: rotate(0deg);
         }
         form:invalid + button {
         pointer-events: none;
         opacity: 0.25;
         }
         @keyframes spin {
         from {
         transform: rotate(0deg);
         width: 24px;
         opacity: 1;
         margin-right: 12px;
         }
         to {
         transform: rotate(360deg);
         width: 24px;
         opacity: 1;
         margin-right: 12px;
         }
         }
         .size-previeww {
         background-color: #0070C9;
         opacity: 0.1;
         z-index: 1;
         position: absolute;
         top: 0;
         left: 0;
         }
         .product-preview{
         color: #444444;
         -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
         border: 0;
         font-size: 100%;
         font: inherit;
         vertical-align: baseline;
         position: relative;
         float: left;
         margin: 0;
         padding: 0;
         list-style-type: none;
         box-sizing: border-box;
         width: 100%;
         }
         .s2x8{
         color: #444444;
         list-style-type: none;
         box-sizing: inherit;
         -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
         margin: 0;
         padding: 0;
         border: 0;
         font-size: 100%;
         font: inherit;
         vertical-align: baseline;
         max-width: 686px;
         cursor: pointer;
         height: 237px;
         } 
         .s16x3{
         color: #444444;
         list-style-type: none;
         cursor: pointer;
         box-sizing: inherit;
         -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
         margin: 0;
         padding: 0;
         font-size: 100%;
         font: inherit;
         vertical-align: baseline;
         position: absolute;
         top: 0;
         left: 0;
         width: 36.59%;
         height: 51.97%;
         z-index: 70;
         border: 3px solid rgb(215, 215, 215);
         border-right: none;
         border-bottom: none;
         border: 3px solid rgb(215, 215, 215);
         border-image: initial;
         box-shadow: rgb(215, 215, 215) -3px -3px 0px 0px inset;
         }
         .s2x4{
         color: #444444;
         list-style-type: none;
         cursor: pointer;
         box-sizing: inherit;
         -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
         margin: 0;
         padding: 0;
         font-size: 100%;
         font: inherit;
         vertical-align: baseline;
         position: absolute;
         top: 0;
         left: 0;
         width: 49.125%;
         height: 65.35%;
         z-index: 60;
         border: 3px solid rgb(215, 215, 215);
         border-right: none;
         border-bottom: none;
         border: 3px solid rgb(215, 215, 215);
         border-image: initial;
         box-shadow: none;
         }
         .s2x6{
         color: #444444;
         list-style-type: none;
         cursor: pointer;
         box-sizing: inherit;
         -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
         margin: 0;
         padding: 0;
         font-size: 100%;
         font: inherit;
         vertical-align: baseline;
         position: absolute;
         top: 0;
         left: 0;
         width: 74.05%;
         height: 65.35%;
         z-index: 50;
         border: 3px solid rgb(215, 215, 215);
         border-right: none;
         border-bottom: none;
         border: 3px solid rgb(215, 215, 215);
         border-image: initial;
         box-shadow: none;
         }
         .s2x8{
         color: #444444;
         list-style-type: none;
         cursor: pointer;
         box-sizing: inherit;
         -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
         margin: 0;
         padding: 0;
         font-size: 100%;
         font: inherit;
         vertical-align: baseline;
         position: absolute;
         top: 0;
         left: 0;
         width: 100%;
         height: 65.35%;
         z-index: 40;
         border: 3px solid rgb(215, 215, 215);
         box-shadow: none;
         }
         .s3x4{
         color: #444444;
         list-style-type: none;
         cursor: pointer;
         box-sizing: inherit;
         -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
         margin: 0;
         padding: 0;
         font-size: 100%;
         font: inherit;
         vertical-align: baseline;
         position: absolute;
         top: 0;
         left: 0;
         width: 49.125%;
         height: 100%;
         z-index: 30;
         border: 3px solid rgb(215, 215, 215);
         box-shadow: none;
         }
         .s3x6{
         color: #444444;
         list-style-type: none;
         cursor: pointer;
         box-sizing: inherit;
         -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
         margin: 0;
         padding: 0;
         font-size: 100%;
         font: inherit;
         vertical-align: baseline;
         position: absolute;
         top: 0;
         left: 0;
         width: 74.05%;
         height: 100%;
         z-index: 20;
         border: 3px solid rgb(215, 215, 215);
         box-shadow: none;
         }
         .s3x8{
         color: #444444;
         list-style-type: none;
         cursor: pointer;
         box-sizing: inherit;
         -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
         margin: 0;
         padding: 0;
         font-size: 100%;
         font: inherit;
         vertical-align: baseline;
         position: absolute;
         top: 0;
         left: 0;
         width: 100%;
         height: 100%;
         z-index: 10;
         box-shadow: -4px -4px 12px 0 rgba(0, 0, 0, 0.05);
         border: 3px solid rgb(215, 215, 215);
         }
         [class^="grid"] {
         position: relative;
         float: left;
         margin: 0;
         padding: 0;
         list-style-type: none;
         /* padding-left: 1.6%; */
         -webkit-box-sizing: border-box;
         -moz-box-sizing: border-box;
         box-sizing: border-box;
         }
         .img-container{
         color: #444444;
         list-style-type: none;
         box-sizing: inherit;
         -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
         margin: 0;
         padding: 0;
         border: 0;
         font-size: 100%;
         font: inherit;
         vertical-align: baseline;
         text-align: center;
         max-width: 176px;
         }
         .preview-for-scale{
         color: #444444;
         list-style-type: none;
         text-align: center;
         box-sizing: inherit;
         -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
         margin: 0;
         padding: 0;
         font-size: 100%;
         font: inherit;
         vertical-align: baseline;
         border: 0;
         max-width: 100%;
         }
         .headline-underline-title, .landing-page .related-products p {
  font-family: "NormsProBold", "Norms Pro", arial, sans-serif;
  color: #000000;
  font-size: 30px;
  line-height: 35px;
  text-align: left;
  text-transform: capitalize; }
  @media (max-width: 768px) {
    .headline-underline-title, .landing-page .related-products p {
      font-size: 26px; } }
  @media (max-width: 580px) {
    .headline-underline-title, .landing-page .related-products p {
      font-size: 22px; } }
  .headline-underline-title:after, .landing-page .related-products p:after {
    content: '';
    display: block;
    margin: 10px 0 20px;
    height: 3px;
    width: 16px;
    background: #CC0000; }
  .headline-underline-title.minimal-margin:after, .landing-page .related-products p.minimal-margin:after {
    margin: 10px 0 15px; }
  .headline-underline-title span.subtitle, .landing-page .related-products p span.subtitle {
    text-align: left;
    color: #000000;
    display: block;
    font-size: 16px; }
         html, body, div, span, applet, object, iframe,
         h1, h2, h3, h4, h5, h6, p, blockquote, pre,
         a, abbr, acronym, address, big, cite, code,
         del, dfn, em, img, ins, kbd, q, s, samp,
         small, strike, strong, sub, sup, tt, var,
         b, u, i, center,
         dl, dt, dd, ol, ul, li,
         fieldset, form, label, legend,
         table, caption, tbody, tfoot, thead, tr, th, td,
         article, aside, canvas, details, embed,
         figure, figcaption, footer, header, hgroup,
         menu, nav, output, ruby, section, summary,
         time, mark, audio, video {
         margin: 0;
         padding: 0;
         border: 0;
         font-size: 100%;
         font: inherit;
         vertical-align: baseline; }
         .row {
         position: relative;
         width: 100%;
         padding: 0;
         margin: 0 auto; }
         [class^="grid"] {
         position: relative;
         float: left;
         margin: 0;
         padding: 0;
         list-style-type: none;
         /*padding-left: 1.6%;*/
         -webkit-box-sizing: border-box;
         -moz-box-sizing: border-box;
         box-sizing: border-box; }
         [class^="grid"].grid-padding {
         padding: 8px; }
         .grid-100 {
         width: 100%; }
         .grid-75 {
         width: 75%; }
         .grid-58 {
         width: 58.33333333%; }
         .grid-50 {
         width: 50%; }
         .grid-25 {
         width: 25%; }
         @media (max-width: 1024px) {
         .grid-smd-83 {
         width: 83.33333333%; }
         .grid-offset-smd-8 {
         margin-left: 8.33333333%; }
         }
         @media (max-width: 768px) {
         .grid-xs-100 {
         width: 100%; }
         .grid-xs-58 {
         width: 58.33333333%; }
         [class^="container"].grid-gutter-xs, [class^="products-list"].grid-gutter-xs {
            padding: 0 18px; }
         [class^="grid"].grid-padding-xs {
            padding: 7px; }
         }
         @media (max-width: 767px) {
         .grid-bt-100 {
         width: 100%; }
         .grid-bt-75 {
         width: 75%; }
         .grid-bt-25 {
         width: 25%; }
         .grid-offset-bt-0 {
         margin-left: 0; }
         [class^="container"].grid-gutter-bt, [class^="products-list"].grid-gutter-bt {
         padding: 0 10px; }
         [class^="grid"].grid-padding-bt {
         padding: 5px; } }
         @media (max-width: 414px) {
         .grid-xxs-66 {
         width: 66.66666667%; }
         .grid-xxs-33 {
         width: 33.333333%; }
         }
         .grid-padding {
         padding: 10px; }
         .grid-padding-lr {
         padding: 0 10px; }
         .grid-padding-tb {
         padding: 10px 0; }
         .row-centered {
         text-align: center; }
         .grid-offset-lg-8 {
  margin-left: 8.33333333%; }
         .img-container {
         text-align: center; }
         .img-container img {
         max-width: 100%; }

         .landing-page .banner-sizing, .bannerconfigurator .banner-sizing {
  background-color: #FFFFFF;
  padding-top: 50px;
  padding-bottom: 50px; }
  @media (max-width: 767px) {
    .landing-page .banner-sizing, .bannerconfigurator .banner-sizing {
      padding-top: 12px;
      padding-bottom: 30px; } }
  @media (max-width: 767px) {
    .landing-page .banner-sizing h2 + p, .bannerconfigurator .banner-sizing h2 + p {
      padding-top: 0; } }
  .landing-page .banner-sizing .headline-underline-title:after, .landing-page .banner-sizing .related-products p:after, .landing-page .related-products .banner-sizing p:after, .bannerconfigurator .banner-sizing .headline-underline-title:after, .bannerconfigurator .banner-sizing .landing-page .related-products p:after, .landing-page .related-products .bannerconfigurator .banner-sizing p:after {
    margin-bottom: 23px; }
  .landing-page .banner-sizing p, .bannerconfigurator .banner-sizing p {
    padding-bottom: 15px; }
        @media (max-width: 1024px) {
  .smd-hide {
    display: none; }
  .smd-show {
    display: block; } }
    @media (min-width: 1025px) {
  .desktop-hide {
    display: none; } }
      
        
        
    
         @media (max-width: 767px) {
         .below-tablet-hide {
         display: none; }
         .below-tablet-show {
         display: block; } }
         @media (min-width: 768px){
            .container {
                width: 750px;
            }
         }
         @media (min-width: 992px){
            .container {
                width: 970px;
            }
         }
         @media (min-width: 1200px){
            .container {
                width: 1490px;
            }
  
         }
        
         


       

      </style>
   </head>
   <body>
      <section class="container grid-gutter grid-gutter-smd grid-gutter-xs grid-gutter-bt banner-sizing" id="sizes">
         <div class="row">
             <div class="grid-offset-lg-8 grid-25 grid-smd-83 grid-offset-bt-0 grid-bt-100 grid-padding grid-padding-smd grid-padding-xs grid-padding-bt">
                 <h2 class="headline-underline-title"><b>Available Sizes</b></h2>
                 <p style="    color: gray;
                 margin-block-end: 1rem;">Display your banner horizontally or vertically. Find the perfect size for your message and space.</p>
                 <p style="    color: gray;">For custom sizes, see a store associate or call 1-888-333-3199 to learn more.</p>
             </div>
         <div class="grid-58 grid-offset-smd-8 grid-smd-83 grid-offset-bt-0 grid-bt-100 banner-sizing-grid">
            <div class="row">
               <div class="grid-25 grid-padding grid-padding-smd grid-padding-xs below-tablet-hide">
                  <div class="img-container">
                     <img class="preview-for-scale" src="https://splus-assets.pnimedia.com/dynamic/Content/images/RetailerSpecific/SPLUS/Landing-Page/Banners/banner-guy?v=6a9ac2e4d9091248" alt="">
                  </div>
               </div>
               <div class="grid-75 grid-bt-100 grid-padding grid-padding-smd grid-padding-xs" >
                  <div class="row">
                     <!---mobile image-->
                     <div class="grid-bt-25 grid-xxs-33 desktop-hide smd-hide tablet-hide below-tablet-show">
                        <div class="img-container">
                           <img class="preview-for-scale-mobile" src="https://splus-assets.pnimedia.com/dynamic/Content/images/RetailerSpecific/SPLUS/Landing-Page/Banners/banner-guy?v=6a9ac2e4d9091248" alt="" style="height: 278px;">
                        </div>
                     </div>
                     <div class="grid-100 grid-bt-75 grid-xxs-66 grid-padding-bt product-preview" style="margin-block-end: 3rem;">
                        <div class="banner-preview-wrap" id="banner-sizer" style="height: 245px;">
                           <span class="s16x3 354" style="border: 3px solid rgb(0, 153, 204); box-shadow: none;"></span>
                           <span class="s2x4" style="border: 3px solid rgb(215, 215, 215); box-shadow: none;"></span>
                           <span class="s2x6" style="border: 3px solid rgb(215, 215, 215); box-shadow: none;"></span>
                           <span class="s2x8" style="border: 3px solid rgb(215, 215, 215); box-shadow: none;"></span>
                           <span class="s3x4" style="border: 3px solid rgb(215, 215, 215); box-shadow: none;"></span>
                           <span class="s3x6" style="border: 3px solid rgb(215, 215, 215); box-shadow: none;"></span>
                           <span class="s3x8" style="border: 3px solid rgb(215, 215, 215);"></span>
                        </div>
                     </div>
                     <div class="grid-50 grid-xs-58 grid-bt-100 product-slider-container">
                        <div id="form-wrapper">
                           <form action="/p/quote.php" method="GET">
                              <div id="debt-amount-slider">
                                 <input type="radio" name="debt-amount" id="1" value="1" required checked>
                                 <label for="1" ></label>
                                 <input type="radio" name="debt-amount" id="2" value="2" required>
                                 <label for="2" ></label>
                                 <input type="radio" name="debt-amount" id="3" value="3" required>
                                 <label for="3" ></label>
                                 <input type="radio" name="debt-amount" id="4" value="4" required>
                                 <label for="4" ></label>
                                 <input type="radio" name="debt-amount" id="5" value="5" required>
                                 <label for="5" ></label>
                                 <input type="radio" name="debt-amount" id="6" value="6" required>
                                 <label for="6" ></label>
                                 <input type="radio" name="debt-amount" id="7" value="7" required>
                                 <label for="7" ></label>
                                 <div id="debt-amount-pos"></div>
                              </div>
                           </form>
                        </div>
                     </div>
                     <div class="grid-50 grid-xs-100 product-info-container" style="text-align: center;">
                        <p><span id="product-name">1.6' x 3'</span> Banner | Starting at: <span class="type-bold" id="product-price">$15.99</span></p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
          
    </div>
   </section>
   </body>
   <script>
      $(".s2x6, .354 ,.s2x4,.s2x8,.s3x4,.s3x6,.s3x8").mouseover(function(){
        size($(this).width()+3,$(this).height()+3);
      });
      $(".s2x6, .354 ,.s2x4,.s2x8,.s3x4,.s3x6,.s3x8").mouseout(function(){
      $(".size-previeww").remove();
      });
      $(".354,#1").click(function(){
        $('#1').prop('checked', true);
        $('#product-name').text("1.6' x 3'");
        $('#product-price').text("$15.99");
       $(".354").css({'border': '3px solid rgb(0, 153, 204)', 'box-shadow': 'none'});
       $(".s2x4").css({'border': '3px solid rgb(215, 215, 215)', 'box-shadow': 'none'});
       $(".s2x6").css({'border': '3px solid rgb(215, 215, 215)', 'box-shadow': 'none'});
       $(".s2x8").css({'border': '3px solid rgb(215, 215, 215)', 'box-shadow': 'none'});
       $(".s3x4").css({'border': '3px solid rgb(215, 215, 215)', 'box-shadow': 'none'});
       $(".s3x6").css({'border': '3px solid rgb(215, 215, 215)', 'box-shadow': 'none'});
       $(".s3x8").css({'border': '3px solid rgb(215, 215, 215)', 'box-shadow': 'none'});
      });
      $(".s2x4,#2").click(function(){
        $('#2').prop('checked', true);
        $('#product-name').text("2' x 4'");
        $('#product-price').text("$25.49");
       $(".354").css({'border-top': '3px solid rgb(0, 153, 204)', 'border-right': 'none', 'border-bottom': 'none', 'border-left': '3px solid rgb(0, 153, 204)', 'border-image': 'initial', 'box-shadow': 'rgb(215, 215, 215) -3px -3px 0px 0px inset'});
       $(".s2x4").css({'border': '3px solid rgb(0, 153, 204)', 'box-shadow': 'none'});
       $(".s2x6").css({'border': '3px solid rgb(215, 215, 215)', 'box-shadow': 'none'});
       $(".s2x8").css({'border': '3px solid rgb(215, 215, 215)', 'box-shadow': 'none'});
       $(".s3x4").css({'border': '3px solid rgb(215, 215, 215)', 'box-shadow': 'none'});
       $(".s3x6").css({'border': '3px solid rgb(215, 215, 215)', 'box-shadow': 'none'});
       $(".s3x8").css({'border': '3px solid rgb(215, 215, 215)', 'box-shadow': 'none'});
      });
      
      $(".s2x6,#3").click(function(){
        $('#3').prop('checked', true);
        $('#product-name').text("2' x 6'");
        $('#product-price').text("$38.29");
       $(".354 ").css({'border-top': '3px solid rgb(0, 153, 204)', 'border-right': 'none', 'border-bottom': 'none', 'border-left': '3px solid rgb(0, 153, 204)', 'border-image': 'initial', 'box-shadow': 'rgb(215, 215, 215) -3px -3px 0px 0px inset'});
       $(".s2x4").css({'border-top': '3px solid rgb(0, 153, 204)', 'border-right': 'none', 'border-bottom': 'none', 'border-left': '3px solid rgb(0, 153, 204)', 'border-image': 'initial', 'box-shadow': 'none'});
       $(".s2x6").css({'border': '3px solid rgb(0, 153, 204)', 'box-shadow': 'none'});
       $(".s2x8").css({'border': '3px solid rgb(215, 215, 215)', 'box-shadow': 'none'});
       $(".s3x4").css({'border': '3px solid rgb(215, 215, 215)', 'box-shadow': 'none'});
       $(".s3x6").css({'border': '3px solid rgb(215, 215, 215)', 'box-shadow': 'none'});
       $(".s3x8").css({'border': '3px solid rgb(215, 215, 215)', 'box-shadow': 'none'});
      });
      $(".s2x8,#4").click(function(){
        $('#4').prop('checked', true);
        $('#product-name').text("2' x 8'");
        $('#product-price').text("$50.99");
       $(".354").css({'border-top': '3px solid rgb(0, 153, 204)', 'border-right': 'none', 'border-bottom': 'none', 'border-left': '3px solid rgb(0, 153, 204)', 'border-image': 'initial', 'box-shadow': 'rgb(215, 215, 215) -3px -3px 0px 0px inset'});
       $(".s2x4").css({'border-top': '3px solid rgb(0, 153, 204)', 'border-right': 'none', 'border-bottom': 'none', 'border-left': '3px solid rgb(0, 153, 204)', 'border-image': 'initial', 'box-shadow': 'none'});
       $(".s2x6").css({'border-top': '3px solid rgb(0, 153, 204)', 'border-right': 'none', 'border-bottom': 'none', 'border-left': '3px solid rgb(0, 153, 204)', 'border-image': 'initial', 'box-shadow': 'none'});
       $(".s2x8").css({'border': '3px solid rgb(0, 153, 204)', 'box-shadow': 'none'});
       $(".s3x4").css({'border': '3px solid rgb(215, 215, 215)', 'box-shadow': 'none'});
       $(".s3x6").css({'border': '3px solid rgb(215, 215, 215)', 'box-shadow': 'none'});
       $(".s3x8").css({'border': '3px solid rgb(215, 215, 215)', 'box-shadow': 'none'});
      });
      $(".s3x4,#5").click(function(){
        $('#5').prop('checked', true);
        $('#product-name').text("3' x 4'");
        $('#product-price').text("$38.29");
       $(".354").css({'border-top': '3px solid rgb(0, 153, 204)', 'border-right': 'none', 'border-bottom': 'none', 'border-left': '3px solid rgb(0, 153, 204)', 'border-image': 'initial', 'box-shadow': 'rgb(215, 215, 215) -3px -3px 0px 0px inset'});
       $(".s2x4").css({'border-top': '3px solid rgb(0, 153, 204)', 'border-right': '3px solid rgb(0,153,204)', 'border-bottom': 'none', 'border-left': '3px solid rgb(0, 153, 204)', 'border-image': 'initial', 'box-shadow': 'none'});
       $(".s2x6").css({'border': '3px solid rgb(215, 215, 215)', 'box-shadow': 'none'});
       $(".s2x8").css({'border': '3px solid rgb(215, 215, 215)', 'box-shadow': 'none'});
       $(".s3x4").css({'border': '3px solid rgb(0, 153, 204)', 'box-shadow': 'none'});
       $(".s3x6").css({'border': '3px solid rgb(215, 215, 215)', 'box-shadow': 'none'});
       $(".s3x8").css({'border': '3px solid rgb(215, 215, 215)', 'box-shadow': 'none'});
      });
      $(".s3x6,#6").click(function(){
        $('#6').prop('checked', true);
        $('#product-name').text("3' x 6'");
        $('#product-price').text("$57.49");
       $(".354").css({'border-top': '3px solid rgb(0, 153, 204)', 'border-right': 'none', 'border-bottom': 'none', 'border-left': '3px solid rgb(0, 153, 204)', 'border-image': 'initial', 'box-shadow': 'rgb(215, 215, 215) -3px -3px 0px 0px inset'});
       $(".s2x4").css({'border-top': '3px solid rgb(0, 153, 204)', 'border-right': 'none', 'border-bottom': 'none', 'border-left': '3px solid rgb(0, 153, 204)', 'border-image': 'initial', 'box-shadow': 'none'});
       $(".s2x6").css({'border-top': '3px solid rgb(0, 153, 204)', 'border-right': '3px solid rgb(0, 153, 204)', 'border-bottom': 'none', 'border-left': '3px solid rgb(0, 153, 204)', 'border-image': 'initial', 'box-shadow': 'none'});
       $(".s2x8").css({'border': '3px solid rgb(215, 215, 215)', 'box-shadow': 'none'});
       $(".s3x4").css({'border-top': 'none', 'border-right': 'none', 'border-bottom': '3px solid rgb(0, 153, 204)', 'border-left': '3px solid rgb(0, 153, 204)', 'border-image': 'initial', 'box-shadow': 'rgb(215, 215, 215) -3px 0px 0px 0px inset'});
       $(".s3x6").css({'border': '3px solid rgb(0, 153, 204)', 'box-shadow': 'none'});
       $(".s3x8").css({'border': '3px solid rgb(215, 215, 215)', 'box-shadow': 'none'});
      });
      $(".s3x8,#7").click(function(){
        $('#7').prop('checked', true);
        $('#product-name').text("3' x 8'");
        $('#product-price').text("$76.59");
       $(".354").css({'border-top': '3px solid rgb(0, 153, 204)', 'border-right': 'none', 'border-bottom': 'none', 'border-left': '3px solid rgb(0, 153, 204)', 'border-image': 'initial', 'box-shadow': 'rgb(215, 215, 215) -3px -3px 0px 0px inset'});
       $(".s2x4").css({'border-top': '3px solid rgb(0, 153, 204)', 'border-right': 'none', 'border-bottom': 'none', 'border-left': '3px solid rgb(0, 153, 204)', 'border-image': 'initial', 'box-shadow': 'none'});
       $(".s2x6").css({'border-top': '3px solid rgb(0, 153, 204)', 'border-right': 'none', 'border-bottom': 'none', 'border-left': '3px solid rgb(0, 153, 204)', 'border-image': 'initial', 'box-shadow': 'none'});
       $(".s2x8").css({'border-top': '3px solid rgb(0, 153, 204)', 'border-right': '3px solid rgb(0, 153, 204)', 'border-bottom': 'none', 'border-left': 'none', 'border-image': 'initial', 'box-shadow': 'rgb(215, 215, 215) 0px -3px 0px 0px inset'});
       $(".s3x4").css({'border-top': 'none', 'border-right': 'none', 'border-bottom': '3px solid rgb(0, 153, 204)', 'border-left': '3px solid rgb(0, 153, 204)', 'border-image': 'initial', 'box-shadow': 'rgb(215, 215, 215) -3px 0px 0px 0px inset'});
       $(".s3x6").css({'border-top': 'none','border-right': 'none', 'border-bottom': '3px solid rgb(0, 153, 204)','border-left':'3px solid rgb(0, 153, 204)', 'border-image': 'initial', 'box-shadow': 'rgb(215, 215, 215) -3px 0px 0px 0px inset'});
       $(".s3x8").css({'border': '3px solid rgb(0, 153, 204)', 'box-shadow': 'none'});
      });
      // 
      function size(width,height){
      var newElement = `<div class="size-previeww" style="width:`+width+`px;height:`+height+`px"></div>`;
      $("#banner-sizer").append(newElement);
      }
   </script>
</html>