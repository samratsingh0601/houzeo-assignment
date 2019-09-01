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
	<a href="{{ url('view-data') }}"> Second Page</a>
  </div>
  <div class="row mt-30p">
	<div class="col-md-12" align="middle">
		<button type="button" id="pullPeopleBtn" class="btn btn-basic"><i class="fa fa-users" aria-hidden="true"></i> Pull People</button>
		&nbsp &nbsp
		<button type="button" id="pullFilmsBtn" class="btn btn-basic"><i class="fa fa-film" aria-hidden="true"></i> Pull Films</button>
	</div>
  </div>
<div id="apiContentContainer" class="mt-30p mb-30p" style="display:none;">
  <table id="peopleTable" class="table table-striped" style="overflow: auto; display:none;">
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
  <button type="button" id="previousPageBtn" class="btn btn-info shiftPageBtn"><i class="fa fa-arrow-left" aria-hidden="true"></i> Previous</button>
  <button type="button" id="nextPageBtn" class="btn btn-info float-right shiftPageBtn">Next <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
</div>
@endsection

@section('page-script')
<!-- script tag here -->
<script>
	// pull people buttons action
	$('#pullPeopleBtn').click(function(){
		getPeopleFromApi('https://swapi.co/api/people');
	});
	$('.shiftPageBtn').click(function(){
		getPeopleFromApi($(this).attr('pageurl'));
	});
	// end pull people buttons action
	
	// pull films buttons action
	$('#pullFilmsBtn').click(function(){
		getFilmsFromApi('https://swapi.co/api/films');
	});
	// end pull films buttons action
	
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
					+' </td> <td><button type="button" personurl="'+ value.url +'" class="btn btn-success save-person-btn"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save Data</button></td> </tr>'
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
				$('#filmsTable').css('display', 'none');
				$('#peopleTable').css('display', 'table');
				$('#apiContentContainer').css('display', 'block');
				$('html,body').animate({scrollTop: $('#apiContentContainer').offset().top},500);
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
					+' </td> <td><button type="button" filmurl="'+ value.url +'" class="btn btn-success save-film-btn"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save Data</button></td> </tr>'
					$('#filmsTable tbody').append(row);
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
				$('#peopleTable').css('display', 'none');
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
	$(document).on('click', '.save-person-btn', function(){
		$.ajax({
			url : '{{ url("save-person") }}/?person_url='+$(this).attr('personurl'),
			type : 'GET',
			success : function(data) {
				result = JSON.parse(data);
				if(result.status){
					alert(result.message);
				}else{
					alert(result.message);
				}
				$('html,body').animate({scrollTop: $('#apiContentContainer').offset().top},500);
			},
			error : function(request,error)
			{
				console.log("Request: "+JSON.stringify(request));
			}
		});
	});
	// end save person to database function
	
	// save film to database function
	$(document).on('click', '.save-film-btn', function(){
		$.ajax({
			url : '{{ url("save-film") }}/?film_url='+$(this).attr('filmurl'),
			type : 'GET',
			success : function(data) {
				result = JSON.parse(data);
				if(result.status){
					alert(result.message);
				}else{
					alert(result.message);
				}
				$('html,body').animate({scrollTop: $('#apiContentContainer').offset().top},500);
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