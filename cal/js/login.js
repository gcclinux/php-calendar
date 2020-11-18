function addCode(key){
	var code = document.forms[0].code;
	if(code.value.length < 8){
		code.value = code.value + key;
	}
	if(code.value.length == 8){
		document.getElementById("message").style.display = "block";
		setTimeout(submitForm,1000);
	}
}

function submitForm(){
	document.forms[0].submit();
}

function emptyCode(){
	document.forms[0].code.value = "";
}
$('input[type="submit"]').mousedown(function(){
  $(this).css('background', '#2ecc71');
});
$('input[type="submit"]').mouseup(function(){
  $(this).css('background', '#1abc9c');
});

$('#loginform').click(function(){
  $('.login').fadeToggle('slow');
  $(this).toggleClass('white');
});



$(document).mouseup(function (e)
{
    var container = $(".login");

    if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0) // ... nor a descendant of the container
    {
        container.hide();
        $('#loginform').removeClass('white');
    }
});

function showdiv(){
    document.getElementById('ticket').style.display = "block";
}
