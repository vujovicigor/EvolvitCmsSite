
// GOOGLE MAP
var map = '';
var center;

function initialize() {
    var mapOptions = {
      zoom: 16,
      center: new google.maps.LatLng(13.758468, 100.567481),
      scrollwheel: false
    };
  
    map = new google.maps.Map(document.getElementById('map-canvas'),  mapOptions);

    google.maps.event.addDomListener(map, 'idle', function() {
        calculateCenter();
    });
  
    google.maps.event.addDomListener(window, 'resize', function() {
        map.setCenter(center);
    });
}

function calculateCenter() {
  center = map.getCenter();
}

function loadGoogleMap(){
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&' + 'callback=initialize';
    document.body.appendChild(script);
}

$(function(){
  loadGoogleMap();
});

// NIVO LIGHTBOX
$('#portfolio a').nivoLightbox({
        effect: 'fadeScale',
    });

// HIDE MOBILE MENU AFTER CLIKING ON A LINK
$('.navbar-collapse a').click(function(){
    $(".navbar-collapse").collapse('hide');
});

/*
var animTs = [
  { "on":true , "ts":3000 }, // on 3 sec
  { "on":false, "ts":1000 }, // off 1 sec
  { "on":true , "ts":1000 }, // on 1 sec
]

var home = document.getElementById('home')
var anim_index = -1;
function next_timeout(){
  anim_index = (anim_index+1) % animTs.length;
  var img = animTs[anim_index].on?"url('images/home-bg2.png')":"url('images/home-bg.png')"
  home.style.backgroundImage = img
  setTimeout( next_timeout, animTs[anim_index].ts)
}
if (home) {
  next_timeout()
//  document.body.style.backgroundImage = "url('../images/home-bg.jpg') no-repeat"
//  home.style.background = "url('images/team2.jpg') no-repeat"
}
*/