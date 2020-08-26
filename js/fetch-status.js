jQuery(document).ready(function($) {
    prepareDynamicDates();
    $('.timeago').timeago();
    });


$(document).ready(function () {
        // Listen to submit event on the <form> itself!
        $('.frmLike').submit(function (e) {
    
        // Prevent form submission which refreshes page
        e.preventDefault();
    
        // Serialize data
        var formData = $(this).serialize();
    
        // Make AJAX request
        $.post('like-status.php', formData).complete(function() {
            console.log('Success');
        });
        });
    });


function btnLike(x) {
        if ( x.classList.contains( "fa-heart-o") ) {
            x.classList.remove( "fa-heart-o" );
            x.classList.add( "fa-heart" );
        }
        else {
            x.classList.remove( "fa-heart" );
            x.classList.add( "fa-heart-o" );
        }
    }

function btnUnLike(x) {
        if ( x.classList.contains( "fa-heart") ) {
            x.classList.remove( "fa-heart" );
            x.classList.add( "fa-heart-o" );
        }
        else {
            x.classList.remove( "fa-heart-o" );
            x.classList.add( "fa-heart" );
        }
    }


function focusCommentBox(commentBoxId) {
        $("#comment-"+commentBoxId).focus();
    }


/*# Custom Box Post Comment */
$('textarea').each(function () {
    this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
  }).on('input', function () {
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
  });
  /*# End Box Post Status */
    
//Initially hide button - Custom Button Post Comment
