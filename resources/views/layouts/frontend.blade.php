<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <title>{{ $page_title }}</title>

        <meta content="" name="descriptison">

        <meta content="" name="keywords">

        <!-- Vendor CSS Files -->
        <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

        <!--custom css -->
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
        <style>
            html, body, address, blockquote, div, dl, form, h1, h2, h3, h4, h5, h6, ol, p, pre, table, ul, dd, dt, li, tbody, td, tfoot, th, thead, tr, button, del, ins, map, object, a, abbr, acronym, b, bdo, big, br, cite, code, dfn, em, i, img, kbd, q, samp, small, span, strong, sub, sup, tt, var, legend, fieldset, p {
	margin: 0;
	padding: 0;
	border: none;
}
a, input, select, textarea {
	outline: none;
	margin: 0;
	padding: 0;
}
img, fieldset {
	border: 0;
}
a {
	outline: none;
	border: none;
}
img {
	max-width: 100%;
	height: auto;
	width: auto\9;
	vertical-align: middle;
	border: none;
	outline: none;
}

article, aside, details, figcaption, figure, footer, header, hgroup, menu, nav, section {
	display: block;
	margin: 0;
	padding: 0;
}
div, h1, h2, h3, h4, span, p, input, form, img, hr, img, a {
	margin: 0;
	padding: 0;
	border: none;
}

.bannerTop{
	background:url(../images/banner.jpg) no-repeat;
	width:100%;
	height:601px;
	background-size:cover;
}
.logo{
	text-align:center;
	padding:50px 0 0 0;
}
.topText{
	margin:80px auto 0;
	display:table;
	text-align:center;
}
.topText h1{
	font-size:35px;
	font-weight:700;
	color:#222222;
	text-transform:uppercase;
}
.topText p{
	font-size:25px;
	font-weight:500;
	color:#222222;
}
.image1{
	width:100%;
	text-align:center;
	margin:-150px 0 0 0;
}
.bgIn{
	background:url(../images/bg1.png) center center no-repeat;
	width:95%;
	padding:110px 0 0 0;
	height:443px;
	margin:60px auto 0;
	position:relative;
}
.bgIn:after{
	background:url(../images/leaf.png) center center no-repeat;
	width:330px;
	height:229px;
	content:"";
	position:absolute;
	bottom: -50px;
    right: -130px;
}
.welcomeBx{
	background:#ffffff;
	width:100%;
	border-radius:5px;
	box-shadow:0 0px 17px rgb(0 0 0 / 16%);
	text-align:center;
	padding:50px;	
}
.welcomeBx h1{
	font-size:43px;
	font-weight:500;
	color:#3fb99d;
}
.welcomeBx h1 span{
	font-weight:700;
	color:#f37c44;
	text-transform:uppercase;
}
.welcomeBx p{
	font-size:18px;
	font-weight:400;
	color:#222222;
	    padding: 20px 0 0 0;
}
.box1{
	background:#ededed;
	width:100%;
	display:inline-block;
	padding:90px 0;
	margin:90px 0 90px 0;
}
.heading1{
	font-size:43px;
	font-weight:700;
	color:#222222;
	position:relative;
	margin:0 auto;
	display:table;
	text-transform:uppercase;
}
.heading1:after{
	background:url(../images/border.png) center center no-repeat;
	width:88px;
	height:49px;
	content:"";
	position:absolute;
	left:0;
	right:0;
	margin:0 auto;
	bottom:-40px;
}
.list1{
	margin:90px auto 0;
}
.list1 ul{
	list-style:none;
	display:flex;
}
.list1 ul li{
	display:inline-block;
	width:25%;
	text-align:center;
	padding:0 15px;
}
.list1 ul li .icon1{
	width:100px;
	height:100px;
	border-radius:100%;
	text-align:center;
	background:#41c2a4;
	border:5px solid #f48034;
	box-shadow:0 0 3px rgba(0,0,0,0.5);
	padding:25px 0 0 0;
	margin:0 auto;
	transform:scale(1.0); 
	-webkit-transition:1s all;
}
.list1 ul li:hover .icon1{
	transform:scale(0.9);
	transition:1s all;
	-webkit-transition:1s all;
}
.list1 ul li h1{
	font-size:18px;
	font-weight:600;
	color:#222222;
	text-transform:uppercase;
	padding:20px 0 0 0;
	line-height: 27px;
}
.outer1{
	width:100%;
	display:inline-block;
}
.leftImg{
	width:50%;
	display:inline-block;
}
.inBx{
	width:48%;
	display:inline-block;
}
.padding1{
	padding:0 0 0 50px;
}
.inBx h1{
	font-size:48px;
	font-weight:700;
	color:#f88029;
	text-transform:uppercase;
}
.inBx h2{
	font-size:35px;
	font-weight:700;
	color:#f88029;
	text-transform:uppercase;
}
.inBx p{
	font-size:19px;
	font-weight:400;
	color:#f88029;
}
.buttonIn{
	margin:30px 0 0 0;
}
.buttonIn a{
	display:inline-block;
	padding:0 20px 0 0;
}
.buttonIn a img{
	transform:scale(1.0);
	transition:1s all;
	-webkit-transition:1s all;
}
.buttonIn a:hover img{
	transform:scale(0.9);
	transition:1s all;
	-webkit-transition:1s all;
}
.bx2 {
    background: #ededed;
    width: 100%;
    display: inline-block;
    padding: 90px 0 150px;
    margin: 90px 0 0 0;
}
.footer{
	background: #41c2a4;
    width: 100%;
    display: inline-block;
}
.infoFooter{
	margin:-50px auto 0;
	display:table;
}
.infoFooter ul{
	list-style:none;
	text-align:center;
}
.infoFooter ul li{
	display:inline-block;
	padding:0 25px;
}
.infoFooter ul li .icon2{
	width:90px;
	height:90px;
	border-radius:100%;
	text-align:center;
	background:#ffffff;
	border:5px solid #41c2a4;
	box-shadow:0 0 3px rgba(0,0,0,0.5);
	padding:25px 0 0 0;
	margin:0 auto;
	transform:scale(1.0);
	transition:1s all;
	-webkit-transition:1s all;
}
.infoFooter ul li:hover .icon2{
	transform:scale(0.9);
	transition:1s all;
	-webkit-transition:1s all;
}
.infoFooter ul li p{
	font-size:22px;
	font-weight:500;
	color:#ffffff;
	padding:15px 0 0 0;
}
.infoFooter ul li p a{
	color:#ffffff;
}
.link-Foot{
	margin:50px auto 50px;
	display:table;
}
.link-Foot a{
	font-size:15px;
	font-weight:400;
	color:#ffffff;
	padding:0 20px;
	text-decoration:underline;
}
.copyright{
	font-size:15px;
	font-weight:400;
	color:#ffffff;
	border-top:1px solid #37a58b;
	text-align:center;
	padding:50px 0;
}
.infoTop{
	font-size:15px;
	font-weight:400;
	color:#222222;
	padding:90px 0 0;
}
.text1{
	margin:50px 0;
}
.text1 h1{
	font-size:30px;
	font-weight:700;
	color:#f48034;
}
.text1 p{
	font-size:15px;
	font-weight:400;
	color:#222222;
	padding:10px 0 0 0;
}
.text1 p a{
	color:#f48034;
}
.text1 h2{
	font-size:20px;
	font-weight:700;
	color:#f48034;
	padding:20px 0 0 0;
}
.padding0{
	padding:0 0 50px;
}
.listPage{
	margin:30px 0 0 0;
}
.listPage ul{
	display:block;
}
.listPage ul li{
	display:block;
	font-size:14px;
	font-weight:400;
	color:#222222;
	padding:0 0 10px 30px;
	background:url(../images/arrow.png) 10px 5px no-repeat;
}
.row1{
	margin:30px 0 0 0;
}
.row1 ul{
	display:block;
	border-bottom:1px solid #898989;
	padding:10px 0;
}
.row1 ul li:nth-child(1){
	width:34%;
	display:inline-block;
}
.row1 ul li:nth-child(2){
	width:20%;
	display:inline-block;
}
.row1 ul li:nth-child(3){
	width:34%;
	display:inline-block;
}
.row1 ul li{
	font-size:14px;
	font-weight:400;
	color:#222222;
	text-align:left;
	vertical-align:top;
}
.row1 ul li h1{
	font-size:16px;
	font-weight:700;
	color:#222222;
}

.list1 ul{
	list-style:none;
	display:flex;
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .inBx h1 {
        font-size: 25px;
    }

    .list1 ul {
        flex-direction: column;
    }

    .bgIn:after {
        display: none;
    }
    .list1 ul li {
        width: 100%;
        margin-bottom: 30px;}
}
        </style>
        <!--custom css-->
    </head>
    <body>
       
        
        <!-- ======= Header ======= -->

        @yield('content')

        <!-- ======= Footer ======= -->
        <footer id="footer">
        </footer>

        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        @stack('scripts')
    </body>
</html>