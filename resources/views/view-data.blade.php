@extends('layouts.app')

@section('page-style')
<!-- style tag here -->
<style>
	.mt-30p {
		margin-top: 30px;
	}
	.mb-30p {
		margin-bottom: 30px;
	}
</style>
@endsection

@section('page-content')
  <div class="row mt-30p">
	<a href="{{ url('/') }}"> Home Page</a>
  </div>
<div id="apiContentContainer" class="mt-30p mb-30p" style="display:none;">
  <h3> Films </h3>
  <table id="filmsTable" class="table table-striped" style="overflow: auto; display:none;">
    <thead>
      <tr>
        <th>Title</th>
        <th>Episode Id</th>
        <th>Director</th>
        <th>Producer</th>
        <th>Release Date</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
			
		</td>
      </tr> 
    </tbody>
  </table>
  <hr>
  <h3> Peoples </h3>
  <table id="peopleTable" class="table table-striped mt-30p" style="overflow: auto; display:none;">
    <thead>
      <tr>
        <th>Name</th>
        <th>Height</th>
        <th>Mass</th>
        <th>Hair Color</th>
        <th>Skin Color</th>
        <th>Eye Color</th>
        <th>Birth Year</th>
        <th>Gender</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
			
		</td>
      </tr> 
    </tbody>
  </table>
  
  <button type="button" id="previousPageBtn" class="btn btn-info shiftPageBtn"><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous</button>
  <button type="button" id="nextPageBtn" class="btn btn-info float-right shiftPageBtn">Next <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
</div>

<!-- view person films modal -->
<div id="filmsModal" class="modal fade" role="dialog">
  <div class="modal-dialog" style="max-width: 900px;">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Films</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
	  
  <table id="personFilmsModalTable" class="table table-striped mt-30p" style="overflow: auto;">
    <thead>
      <tr>
        <th>Title</th>
        <th>Episode Id</th>
        <th>Director</th>
        <th>Producer</th>
        <th>Release Date</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
			
		</td>
      </tr> 
    </tbody>
  </table>
	  
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!-- view films characters modal -->
<div id="charactersModal" class="modal fade" role="dialog">
  <div class="modal-dialog" style="max-width: 900px;">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Characters</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
	  
  <table id="filmCharactersModalTable" class="table table-striped mt-30p" style="overflow: auto;">
    <thead>
      <tr>
        <th>Name</th>
        <th>Height</th>
        <th>Mass</th>
        <th>Hair Color</th>
        <th>Skin Color</th>
        <th>Eye Color</th>
        <th>Birth Year</th>
        <th>Gender</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
			
		</td>
      </tr> 
    </tbody>
  </table>
	  
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
@endsection

@section('page-script')
<!-- script tag here -->
<script>
	$(document).ready(function(){
		getFilmsFromApi('https://swapi.co/api/films');
		getPeopleFromApi('https://swapi.co/api/people');
	});
	
	// pull people buttons action
	$('.shiftPageBtn').click(function(){
		getPeopleFromApi($(this).attr('pageurl'));
	});
	// end pull people buttons action
	
	// get people from api function
	function getPeopleFromApi(pageUrl){
		$.ajax({
			url : pageUrl,
			type : 'GET',
			success : function(data) {
				$('#peopleTable tbody').empty();
				$.each(data.results, function (key, value) {
					var row = '<tr><td> ' + value.name 
					+ ' </td> <td> ' + value.height
					+' </td> <td>' + value.mass
					+' </td> <td>' + value.hair_color
					+' </td> <td>' + value.skin_color
					+' </td> <td>' + value.eye_color
					+' </td> <td>' + value.birth_year
					+' </td> <td>' + value.gender
					+' </td> <td><button type="button" personurl="'+ value.url +'" class="btn btn-success person-view-films-btn"><i class="fa fa-eye" aria-hidden="true"></i> View Films</button></td> </tr>';
					$('#peopleTable tbody').append(row);
				});
				if(data.next !== null){
					$('#nextPageBtn').attr('pageurl', data.next);
					$('#nextPageBtn').removeAttr('disabled');
				}else{
					$('#nextPageBtn').attr('disabled', 'disabled');
				}
				if(data.previous !== null){
					$('#previousPageBtn').attr('pageurl', data.previous);
					$('#previousPageBtn').removeAttr('disabled');
				}else{
					$('#previousPageBtn').attr('disabled', 'disabled');
				}
				$('#peopleTable').css('display', 'table');
				$('#apiContentContainer').css('display', 'block');
				$('html,body').animate({scrollTop: $('#peopleTable').offset().top},500);
			},
			error : function(request,error)
			{
				console.log("Request: "+JSON.stringify(request));
			}
		});
	}
	// end get people from api function
	
	// get films from api function
	function getFilmsFromApi(pageUrl){
		$.ajax({
			url : pageUrl,
			type : 'GET',
			success : function(data) {
				$('#filmsTable tbody').empty();
				console.log(data);
				$.each(data.results, function (key, value) {
					var row = '<tr><td> ' + value.title 
					+ ' </td> <td> ' + value.episode_id
					+' </td> <td>' + value.director
					+' </td> <td>' + value.producer
					+' </td> <td>' + value.release_date
					+' </td> <td><button type="button" filmurl="'+ value.url +'" class="btn btn-success film-view-characters-btn"><i class="fa fa-eye" aria-hidden="true"></i> View Characters</button></td> </tr>';
					$('#filmsTable tbody').append(row);
				});
				$('#filmsTable').css('display', 'table');
				$('#apiContentContainer').css('display', 'block');
				$('html,body').animate({scrollTop: $('#apiContentContainer').offset().top},500);
			},
			error : function(request,error)
			{
				console.log("Request: "+JSON.stringify(request));
			}
		});
	}
	// end get films from api function
	
	// save person to database function
	$(document).on('click', '.person-view-films-btn', function(){
		$.ajax({
			url : '{{ url("get-person-films") }}/?person_url='+$(this).attr('personurl'),
			type : 'GET',
			success : function(data) {
				result = JSON.parse(data);
				if(result.status){
					$('#personFilmsModalTable tbody').empty();
					$.each(result.films, function (key, value) {
						var row = '<tr><td> ' + value.title 
						+ ' </td> <td> ' + value.episode_id
						+' </td> <td>' + value.director
						+' </td> <td>' + value.producer
						+' </td> <td>' + value.release_date
						+' <td></tr>';
						$('#personFilmsModalTable tbody').append(row);
					});
					$('#filmsModal').modal('show');
				}else{
					console.log(result.message);
					alert('Not found in local database. Please save the person first');
				}
			},
			error : function(request,error)
			{
				console.log("Request: "+JSON.stringify(request));
			}
		});
	});
	// end save person to database function
	
	// save film to database function
	$(document).on('click', '.film-view-characters-btn', function(){
		$.ajax({
			url : '{{ url("get-film-characters") }}/?film_url='+$(this).attr('filmurl'),
			type : 'GET',
			success : function(data) {
				result = JSON.parse(data);
				if(result.status){
					$('#filmCharactersModalTable tbody').empty();
					$.each(result.characters, function (key, value) {
						var row = '<tr><td> ' + value.name 
						+ ' </td> <td> ' + value.height
						+' </td> <td>' + value.mass
						+' </td> <td>' + value.hair_color
						+' </td> <td>' + value.skin_color
						+' </td> <td>' + value.eye_color
						+' </td> <td>' + value.birth_year
						+' </td> <td>' + value.gender
						+' </td> </tr>';
						$('#filmCharactersModalTable tbody').append(row);
					});
					$('#charactersModal').modal('show');
				}else{
					console.log(result.message);
					alert('Not found in local database. Please save the film first');
				}
			},
			error : function(request,error)
			{
				console.log("Request: "+JSON.stringify(request));
			}
		});
	});
	// end save person to database function
</script>
@endsection