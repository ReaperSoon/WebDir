function playVideo(url) {
    stopAndHideAudio();
    var name = document.getElementById('video-name');
    var player = document.getElementById('video-player');
    
    name.textContent = url;
    player.pause();

    player.src = url;

    player.load();
    player.play();
    scrollTo(player, 500);
}

function playAudio(url) {
    stopAndHideVideo();
    var player = document.getElementById('audio-player');
    player.pause();

    player.src = url;

    player.load();
    player.play();
}

function stopAndHideVideo() {
    var player = document.getElementById('video-player');
    player.pause();

    player.src = "";
}

function stopAndHideAudio() {
    var player = document.getElementById('audio-player');
    player.pause();

    player.src = "";
}

function setupVideo()
{
    var v = document.getElementById('video-player');
    v.addEventListener('mouseover', function() { this.controls = true; }, false);
    v.addEventListener('mouseout', function() { this.controls = false; }, false);
}
window.addEventListener('load', setupVideo, false);

// c = element to scroll to or top position in pixels
// e = duration of the scroll in ms, time scrolling
// d = (optative) ease function. Default easeOutCuaic
function scrollTo(c,e,d){d||(d=easeOutCuaic);var a=document.documentElement;if(0===a.scrollTop){var b=a.scrollTop;++a.scrollTop;a=b+1===a.scrollTop--?a:document.body}b=a.scrollTop;0>=e||("object"===typeof b&&(b=b.offsetTop),"object"===typeof c&&(c=c.offsetTop),function(a,b,c,f,d,e,h){function g(){0>f||1<f||0>=d?a.scrollTop=c:(a.scrollTop=b-(b-c)*h(f),f+=d*e,setTimeout(g,e))}g()}(a,b,c,0,1/e,20,d))};
function easeOutCuaic(t){
    t--;
    return t*t*t+1;
}
$(document).ready(function(){
    jQuery( "#menu a.oniframe" ).click(function(event) {
        event.preventDefault();
        jQuery.magnificPopup.open({
          items: {              
            src: jQuery(this).attr('href'),          
            type: 'iframe'
        }
    });
    });
    Dropzone.autoDiscover = false;

    var dropzone = new Dropzone("#uploadFile", {
       previewsContainer: "#upload-preview",
       url: "/upload",
       maxFilesize: 100000,
       timeout: 3600000,
       clickable: false,
       ignoreHiddenFiles: true,
       init: function() {
          this.on("success", function(file, responseText) {
            file.previewTemplate.appendChild(document.createTextNode(responseText));
        });
      }
  });
    dropzone.on('sending', function(file, xhr, formData){
        formData.append('filepath', typeof file.fullPath !== 'undefined' ? file.fullPath : file.name);
        formData.append('path', location.pathname.substring(1));
    });

    /* Get background image */
    /*$.ajax({
        dataType: "json",
        url: 'https://api.unsplash.com/photos/random?query=landscape&featured&orientation=landscape',
        headers: {
            "Authorization": "Client-ID e1c4ece99d2ca64e5f541de11c16d66529394c3084ae2f6e988ca1b86212fee6"
        },
        success: function(result) {
            $('html').css("background-image", "url('" + result.urls.full + "')");
        }
    });*/
});

function renameFile(path, filename) {
    var newname = prompt("Rename this file:", filename);
    $.ajax({
        type: "GET",
        url: '/handler?action=rename&oldname=' + path + '/' + filename + '&newname=' + path + '/' + newname,
        success: function() {
            location.reload();
        }
    });
}

function deleteFile(path, filename) {
    if (confirm('Do you realy want to remove ' + filename + ' ?!')) {
            $.ajax({
            type: "GET",
            url: '/handler?action=delete&path=' + path + '&file=' + filename,
            success: function() {
                location.reload();
            }
        });
    }
}

function showDirSize(dir, elem) {
    $(elem).attr("onclick", null);
    $(elem).removeClass("show");
    $(elem).addClass("loading");
    $(elem).text(" ");
    $.ajax({
        type: "GET",
        url: '/handler?action=dirsize&path=' + dir,
        success: function(size) {
            $(elem).removeClass("loading");
            $(elem).text(size);
        }
    });
}


/*$(document).ready(function() {
    $(document).on('click', function(event) {
        $(".dropbtn").not(event.target).siblings('.dropdown-content').removeClass("show");
    });
    $(".dropbtn").click(function(event) {
        $(this).siblings('.dropdown-content').toggleClass("show");
    });
});*/
