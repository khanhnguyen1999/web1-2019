/*# Custom dropdown nav */
$(document).ready(function(){
  $(".nav .dropdown").focusin( function (){
     $(this).find(".dropdown-menu").each(function(){
       $(this).css({"display":'block','opacity':'1','top':'38px'}); 
     });
  });
  
    $(".nav .dropdown").focusout( function (){
     $(this).find(".dropdown-menu").each(function(){
       $(this).css({"display":'block','opacity':'0','top':'0px'}); 
     });
  });
  
  
//   $(".navbar-brand").click( function (){ 
//    alert("js working"); 
//   });
  
});

/*# Custom clicked list-group-item_fix right bar */
// $(document).ready(function() {
//   $('.list-group-item_fix').click(function(e) {
//     e.preventDefault();
//     $('.list-group-item_fix').removeClass('active');
//     $(this).addClass('active');
//   });
// });
/*# End list-group-item_fix right bar */

/*# Custom Box Post Status */
$('textarea').each(function () {
  this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
}).on('input', function () {
  this.style.height = 'auto';
  this.style.height = (this.scrollHeight) + 'px';
});
/*# End Box Post Status */

/*# Custom Box Post Status - Modal */
$(document).ready(function() {
  $('#PostStatusModal').on('shown.bs.modal', function() {
    $('#statusInput').trigger('focus');
  });
});
/*# END Box Post Status - Modal */

/*# Custom Box Post Status - Upload Img */
$('#OpenImgUpload').click(function(){ $('#imgupload').trigger('click'); });
/*# END Box Post Status - Upload Img */

/*# Custom Box Post Status - DropdownMenu Permission */
$(".dropdown-menu.tloc a").click(function(){
  var selText = $(this).html();
  $(this).parents('.dropdown').find('.dropdown-toggle').html(selText+'<span class="caret"></span>');
});
/*# END Box Post Status - DropdownMenu Permission */

// Material Select Initialization
$(document).ready(function() {
  $('.mdb-select').materialSelect();
  });


//Initially hide button - Custom Button Post Status
$('#btn_postStatus').attr("disabled", true);
//Textarea keyup function
$('textarea[name=content_status]').keyup(function(){
  if($.trim($(this).val()).length  <= 0)$('#btn_postStatus').attr("disabled", true)
  else $('#btn_postStatus').attr("disabled", false)
});

$('label[id=image]').click(function(){
  $('#btn_postStatus').attr("disabled", false);
});


////Multiple Image - Box post status
document.getElementById("files").onchange = function () {
  var reader = new FileReader();

  reader.onload = function (e) {
      // get loaded data and render thumbnail.
      document.getElementById("image").src = e.target.result;
  };

  // read the image file as a data URL.
  reader.readAsDataURL(this.files[0]);
};
function handleFileSelect(evt) {
  var files = evt.target.files;

  // Loop through the FileList and render image files as thumbnails.
  for (var i = 0, f; f = files[i]; i++) {

    // Only process image files.
    if (!f.type.match('image.*')) {
      continue;
    }

    var reader = new FileReader();

    // Closure to capture the file information.
    reader.onload = (function(theFile) {
      return function(e) {
        // Render thumbnail.
        var span = document.createElement('span');
        span.innerHTML = 
        [
          '<img style="height: 75px; border: 1px solid #000; margin: 5px" src="', 
          e.target.result,
          '" title="', escape(theFile.name), 
          '"/>'
        ].join('');
        
        document.getElementById('list').insertBefore(span, null);
      };
    })(f);

    // Read in the image file as a data URL.
    reader.readAsDataURL(f);
  }
}
document.getElementById('files').addEventListener('change', handleFileSelect, false);


// Read in the image file as a data URL.
document.getElementById('files').onclick = function() {
  document.getElementById('files').click();
};

$('input[type=file]').change(function (e) {
  $('#filePath').html($(this).val());
});

// Auto width position-fixed
var new_width = $('#fixed_container').width();
$('#fixed_container_position').width(new_width); 
var new_height = $('#fixed_container').height();
$('#fixed_container_position').height(new_height); 


// Fetch load_status index Jquery Ajax
$(document).ready(function(){
 
  var limit = 5;
  var start = 0;
  var action = 'inactive';
  function load_country_data(limit, start)
  {
   $.ajax({
    url:"fetch-status.php",
    method:"POST",
    data:{limit:limit, start:start},
    cache:false,
    success:function(data)
    {
     $('#load_status').append(data);
     if(data == '')
     {
      $('#load_status_message').html("<button type='button' class='btn btn-info'>Không tìm thấy dữ liệu nữa để tải!</button>");
      action = 'active';
     }
     else
     {
      $('#load_status_message').html("<div class='card mx-auto my-3'><div class='card-body'><div class='avatar-placeholder placeholder rounded-circle'></div><div class='text-placeholder placeholder w-75'></div><div class='text-placeholder placeholder w-50'></div><div class='text-placeholder placeholder w-75'></div><div class='text-placeholder placeholder w-100'></div><div class='text-placeholder placeholder w-100'></div></div></div>");
      action = "inactive";
     }
    }
   });
  }
 
  if(action == 'inactive')
  {
   action = 'active';
   load_country_data(limit, start);
  }
  $(window).scroll(function(){
   if($(window).scrollTop() + $(window).height() > $("#load_status").height() && action == 'inactive')
   {
    action = 'active';
    start = start + limit;
    setTimeout(function(){
     load_country_data(limit, start);
    }, 1000);
   }
  });
  
 });


// Time Ago
 jQuery(document).ready(function($) {
  prepareDynamicDates();
  $('.timeago').timeago();
});
 
// // sessionStorage Box Post Status
// $(document).ready(function() {
//   function init() { 
//      if (sessionStorage["content_status"]) {
//         $("#content_status").val(sessionStorage["content_status"]);
//      }
//   }
//   init();
// });

// $(".stored").keyup(function() {
//   sessionStorage[$(this).attr("name")] = $(this).val();
// });

// $(window).onbeforeunload (function(){
//   window.sessionStorage.clear();
//   $("#sessionStorage")
//      .get(0)
//      .reset();
// });

// $("#btn_postStatus").click(function() {
//   window.localStorage.clear();
//   $("#sessionStorage")
//      .get(0)
//      .reset();
//   document.getElementById('content_status').value = "";
// });


// // Button Add Friend
// $(document).ready(function () {
//   $(".btn_profile_avatar button").click(function () {
//       if ($(this).hasClass('btn_addfr')) {
//           $(this).html('Đã gửi lời mời kết bạn').toggleClass('btn_addfr btn_sendedfr');
//       } else {
//           $(this).html('Thêm vào bạn bè').toggleClass('btn_sendedfr btn_addfr');
//       }
//   });
// });

// Hover dropdown-menu profile-avt-fr
const $dropdown = $(".dropdown.profile-avt-fr");
const $dropdownToggle = $(".dropdown-toggle");
const $dropdownMenu = $(".dropdown-menu");
const showClass = "show";
 
$(window).on("load resize", function() {
  if (this.matchMedia("(min-width: 768px)").matches) {
    $dropdown.hover(
      function() {
        const $this = $(this);
        $this.addClass(showClass);
        $this.find($dropdownToggle).attr("aria-expanded", "true");
        $this.find($dropdownMenu).addClass(showClass);
      },
      function() {
        const $this = $(this);
        $this.removeClass(showClass);
        $this.find($dropdownToggle).attr("aria-expanded", "false");
        $this.find($dropdownMenu).removeClass(showClass);
      }
    );
  } else {
    $dropdown.off("mouseenter mouseleave");
  }
});


// Auto Focus Modal Content Status
$('#PostStatusModal').on('shown.bs.modal', function () {
  $('#content_status').focus();
})


/* // Auto reload when resize window
$(window).resize(function(){location.reload();}); */


// Readmore
$(document).ready(function(){
  length = 200;
  cHtml = $(".content_readMore").html();
  cText = $(".content_readMore").text().substr(0, length).trim();
  $(".content_readMore").addClass("compressed").html(cText + "... <a href='#' class='exp'>More</a>");
  window.handler = function()
  {
      $('.exp').click(function(){
          if ($(".content_readMore").hasClass("compressed"))
          {
              $(".content_readMore").html(cHtml + "<a href='#' class='exp'>Less</a>");
              $(".content_readMore").removeClass("compressed");
              handler();
              return false;
          }
          else
          {
              $(".content_readMore").html(cText + "... <a href='#' class='exp'>More</a>");
              $(".content_readMore").addClass("compressed");
              handler();
              return false;
          }
      });
  }
  handler();
});

// Tooltip Notify Nav
$(function () {
  $('.nav-link').tooltip('hide')
})


$(function(){
  $("#upload_link").on('click', function(e){
      e.preventDefault();
      $("#avatar:hidden").trigger('click');
  });
  });
  

// Message Chat
  $(document).ready(function(){
    loadChat();
  });
  
  $('#message_chat').keyup(function(e){

    var to_UserID = $('#toUserID').val();
    var message_chat = $(this).val();

    if( e.which == 13 ){

      $.post('fetch-chat.php?action=SendMessage&toUserID='+to_UserID+'&message_chat='+message_chat, function(response){
        
        loadChat();
        $('#message_chat').val('');

      });

    }

  });

  function loadChat()
  {
    var to_UserID = $('#toUserID').val();
    var from_UserID = $('#fromUserID').val();

    $.post('fetch-chat.php?action=getChat&fromUserID='+from_UserID+'&toUserID='+to_UserID, function(response){
      
      $('.direct-chat-messages').html(response);

    });
  }

  setInterval(function(){
    loadChat();}, 2000);
//END Message Chat


//Popup Chat
$(function(){
  $("#addClass_Chat").click(function () {
            $('#qnimate').addClass('popup-box-on');
              });
            
              $("#removeClass_Chat").click(function () {
            $('#qnimate').removeClass('popup-box-on');
              });
    })
//END Popup Chat

// Remove Chat
$(document).ready(function () {
  // Listen to submit event on the <form> itself!
  $('.frm_rm_Chat').submit(function (e) {

  // Prevent form submission which refreshes page
  e.preventDefault();

  // Serialize data
  var formData = $(this).serialize();

  // Make AJAX request
  $.post('remove-chat.php', formData).complete(function() {
      console.log('Success');
  });
  });
});


