$("head").load("/src/html/head.html");
$(".header").load("/src/html/header.html");
$(".footer").load("/src/html/footer.html");

var titles;
$.get('/JSON.php', function(data) {
    titles = JSON.parse(data);
});

function editDetails() {
		document.getElementById("view").style.display = "none";
		document.getElementById("edit").style.display = "inline";
}

function createNewUser() {
	if (!document.forms["register"]["email"].value.match(/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/)) {
		alert("Please enter a valid email address.");
		return false;
	}
	var password = document.forms["register"]["password"].value;
	if (password.length < 8) {
		alert("Password must contain at least 8 characters.")
		return false;
	}
	var confirmpassword = document.forms["register"]["confirmpassword"].value;
	if (password !== confirmpassword) {
		alert("Password must match confirm password.")
		return false;
	}
	location.href = '../actsetup/';
	return true;
}

function checkPasswords() {
	var password = document.forms["reset"]["password"].value;
	if (password.length < 8) {
		alert("Password must contain at least 8 characters.")
		return false;
	}
	var confirmpassword = document.forms["reset"]["confirmpassword"].value;
	if (password !== confirmpassword) {
		alert("Password must match confirm password.")
		return false;
	}
}

function loginAttempt() {
	if (!document.forms["login"]["email"].value.match(/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/)) {
		alert("Invalid email address.");
		return false;
	}
	var password = document.forms["login"]["password"].value;
	if (password == "") {
		alert("Password cannot be empty.")
		return false;
	}
	return true;
}

function changeFavStatus(id) {
  if (!loggedin) {
    window.location.href = "/login?error=favorite";
  } else {
    $.ajax({
  		url: '/favorites/favorite.php',
  		type: 'GET',
      dataType: 'text',
      contentType: "application/json",
  		data: {
        id: id
      },
  		success: function(response) {
        //console.log(response);
  			if ($("#"+id+"isFavorite").is(":hidden")) {
  				$("#"+id+"isFavorite").show();
  				$("#"+id+"isNotFavorite").hide();
  			} else {
  				$("#"+id+"isFavorite").hide();
  				$("#"+id+"isNotFavorite").show();
  			};
  		}
  	});
  }
}

function updateLoadPlatforms(id) {
  //console.log("called for " + id);
  $.ajax({
    url: '/movie/getPlatforms.php',
    type: 'GET',
    dataType: 'text',
    contentType: "application/json",
    data: {
      id: id
    },
    success: function(response) {
      //console.log("showing data");
      //console.log(id + " " + response);
      $("#" + id + " .platforms").html(response);
    }
  });
}

function updateLoadActors(id) {
  //console.log("called for " + id);
  $.ajax({
    url: '/movie/getActors.php',
    type: 'GET',
    dataType: 'text',
    contentType: "application/json",
    data: {
      id: id
    },
    success: function(response) {
      //console.log("showing data");
      //console.log(id + " " + response);
      $(".actors").html(response);
    }
  });
}

function removeFavoriteCard(id) {
  console.log(id);
  $("#" + id).remove();
}

function checkDate(timestamp) {
  var t = timestamp.split(/[- :]/);
  var d = new Date(Date.UTC(t[0], t[1]-1, t[2], t[3], t[4], t[5]));
  var now = new Date();
  now.setDate(now.getDate() - 7);
  //console.log(d);
  if (d <= now) {
    //console.log("updating");
    return true;
  }
  else {
    //console.log("not updating");
    return false;
  }
}

function getServicesBoolean(services) {
  var servicesReference = ["Netflix", "Hulu", "Disney+", "Amazon Prime Video", "HBO Max"];
  var servicesBool = [];
  for (var item in servicesReference) {
    var object = JSON.stringify(services);
    if (object.includes(servicesReference[item])) {
      servicesBool[servicesReference[item]] = true;
    } else {
      servicesBool[servicesReference[item]] = false;
    }
  }
  return servicesBool;
}

/* $('.movie').each(function(i, obj) {
    var title = $(obj).attr('class').split(' ').pop();
		$(obj).click(function() {
    	window.location = "/movie?title=" + title;
		});
}); */
