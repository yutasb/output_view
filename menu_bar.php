<body>
    <div class='menu-bar'>

        <p class='mainTitle'><a href='mypage.php'>Share Beautiful View</a></p>

        <ul class='menu'>

            <li class='menu1'><a href='myProfile.php'>My Profile</a></li>
            <li class='menu2'><a href='favorite.php'>Favorite</a></li>
            <li class='menu3'><a href='myViewPost.php'>My Post</a></li>
            <li class='menu4'><a href='viewRegister.php'>Post</a></li>
            <span class="menu--bar"></span>





        </ul>
    </div>

    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script>
        $(document).ready(function() {

            if (location.pathname == "myProfile.php") {
                $('.menu li').addClass('menu--bar');
            }

        });
    </script>