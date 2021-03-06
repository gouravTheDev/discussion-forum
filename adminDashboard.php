<?php 
 include 'header.php';
?>
<div class="container mt-2 mb-2">
	<div class="alert alert-warning" id="warning" style="display: none;">Sorry! No data!</div>
	<div class="card shadow">
		<div class="card-body">
			<h1 class="text-center">Admin Dashboard</h1><br>
			<table class="table table-bordered col-md-8 mx-auto">
				<tr>
					<th>User Name:-</th>
					<td id="userName"></td>
				</tr>
				<tr>
					<th>User Type:-</th>
					<td id="userType"></td>
				</tr>
				<tr>
					<th>Phone:-</th>
					<td id="phone"></td>
				</tr>
				<tr>
					<th>Email:-</th>
					<td id="email"></td>
				</tr>
			</table>
			<hr>
			<div class="alert alert-success" id="successMsg" style="display: none;"></div>
			<form>
				<h3 style="font-weight: bold;">Create a Post</h3>
				<input type="text" class="form-control" placeholder="Enter a subject" id="postSubject"><br>
				<textarea class="form-control" cols="4" id="postText" placeholder="Write Something"></textarea><br>
				<button type="button" onclick="createPost();" class="btn btn-success" >Create</button>
			</form><br>
			<h1 class="text-center">All Posts</h1><hr>
			<div id="postsHere"></div>
		</div>
	</div>
</div>
<div id="myModal" class="modal">	
  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <h3 style="font-weight: bold;">Update The Post</h3>
    <div class="form">
		<form>
			<input type="text" class="form-control" placeholder="Enter a subject" id="updatePostSubject"><br>
			<textarea class="form-control" cols="4" id="updatePostText" placeholder="Write Something"></textarea><br>
			<input type="hidden" id="updatePostId">
			<button type="button" onclick="updatePost();" class="btn btn-success" >Update</button>
		</form>
	</div>
  </div>

</div>
<script type="text/javascript">
	window.onload = function(){
		// API CALL TO FETCH USER DATA
		fetch('/backend/API/?fetchDetails')
	        .then(
	          function(response) {
	            if (response.status !== 200) {
	              console.log('Looks like there was a problem. Status Code: ' +
	                response.status);
	              return;
	            }
	              response.json().then(function(data) {
		              if (data.error == null) {
		              	document.getElementById('userName').innerHTML = data.name;
		              	document.getElementById('userType').innerHTML = data.userType;
		              	document.getElementById('phone').innerHTML = data.phone;
		              	document.getElementById('email').innerHTML = data.email;
		              	fetchAllPostsAdmin();
		              }else{
		              	document.getElementById('warning').style.display = "block";
		              }
	            });
	          }
	        )
	        .catch(function(err) {
	          console.log('Fetch Error :-S', err);
       	 });

	};

	// Function to Create Post

	function createPost() {
		var postText = document.getElementById('postText').value;
		var postSubject = document.getElementById('postSubject').value;
		var userName = document.getElementById('userName').innerHTML;
		let formData = new FormData();
      	formData.append('postSubject', postSubject);
      	formData.append('postText', postText);
      	formData.append('userName', userName);
		
		// API CALL TO SUBMIT DATA

		fetch("/backend/API/?createPost", {
            method: "POST",
            body:formData,
        }).then(
            function(response) {
            response.json().then(function(data) {
            	fetchAllPostsAdmin();
            	document.getElementById('successMsg').style.display = 'block';
            	document.getElementById('successMsg').innerHTML = data.msg;
            	document.getElementById('postText').value='';
            	document.getElementById('postSubject').value = '';

            });
          }
        )
        .catch(function(err) {
          console.log('Fetch Error :-S', err);
        });

	}

	// Function to Read Posts

	function fetchAllPostsAdmin() {
		// API CALL TO FETCH USER POSTS
		document.getElementById('successMsg').style.display = 'none';

		fetch('/backend/API/?fetchAllPostsAdmin')
	        .then(
	          function(response) {
	            if (response.status !== 200) {
	              console.log('Looks like there was a problem. Status Code: ' +
	                response.status);
	              return;
	            }
	              response.json().then(function(data) {
	              	console.log(data);
	              	document.getElementById('postsHere').innerHTML = data.data;
	            });
	          }
	        )
	        .catch(function(err) {
	          console.log('Fetch Error :-S', err);
       	 });
	}

	var modal = document.getElementById("myModal");

	// Get the button that opens the modal
	var btn = document.getElementById("updateBtn");

	// Get the <span> element that closes the modal
	var span = document.getElementsByClassName("close")[0];

	function update(postId) {
		modal.style.display = "block";

		// API CALL TO Fetch Single post data

		fetch('/backend/API/?fetchSinglePost&postId='+postId)
	        .then(
	          function(response) {
	            if (response.status !== 200) {
	              console.log('Looks like there was a problem. Status Code: ' +
	                response.status);
	              return;
	            }
	              response.json().then(function(data) {
	              	console.log(data);
	              	document.getElementById('updatePostSubject').value = data.postSubject;
	              	document.getElementById('updatePostText').value = data.postText;
	              	document.getElementById('updatePostId').value = postId;
	            });
	          }
	        )
	        .catch(function(err) {
	          console.log('Fetch Error :-S', err);
       	 });

		
	}

	function updatePost() {
		var postText = document.getElementById('updatePostText').value;
		var postSubject = document.getElementById('updatePostSubject').value;
		var postId = document.getElementById('updatePostId').value;
		let formData = new FormData();
      	formData.append('postSubject', postSubject);
      	formData.append('postText', postText);
      	formData.append('postId', postId);

      	fetch("/backend/API/?updatePost", {
            method: "POST",
            body:formData,
        }).then(
            function(response) {
            response.json().then(function(data) {
            	console.log(data);
            	fetchAllPostsAdmin();
            	document.getElementById('successMsg').style.display = 'block';
            	document.getElementById('successMsg').innerHTML = data.msg;
            	modal.style.display = "none";
            });
          }
        )
        .catch(function(err) {
          console.log('Fetch Error :-S', err);
        });
	}

	function deletePost(postId) {
		let formData = new FormData();
      	formData.append('postId', postId);
		fetch("/backend/API/?deletePost", {
            method: "POST",
            body:formData,
        }).then(
            function(response) {
            response.json().then(function(data) {
              	document.getElementById('successMsg').style.display = 'block';
            	document.getElementById('successMsg').innerHTML = data.msg;
            	fetchAllPostsAdmin();
            });
          }
        )
        .catch(function(err) {
          console.log('Fetch Error :-S', err);
        });
	}


	// When the user clicks on <span> (x), close the modal
	span.onclick = function() {
	  modal.style.display = "none";
	}

	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
	  if (event.target == modal) {
	    modal.style.display = "none";
	  }
	}
</script>