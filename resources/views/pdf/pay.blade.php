<!DOCTYPE html>
<html>
<head>
	<title></title>

<style type="text/css">
	
	h1{
		
		text-align: center;
		font-size: 55px;
	}
	div{
		 width: 300px;
         height: 200px;
         padding: 50px;
         border: 1px solid black;
         margin: auto;
	}
	table,tr,td{
		margin-left: 70px;
		margin-top: 30px;
		font-size: 25px;
		line-height: 20px;
		padding: 10px;

	}
	hr{
		color: #ff0000;
		height: 20px;
		width: 500px;
		margin: auto;
		margin-top: 80px;
		background-color: #ff0000; 
		border-radius: 5px;
	}
	h2{
		font-size: 30px;
		text-align: center;
	}

	
    
</style>
</head>
<body>

Date : <time>{{ $now }}</time>
<img src="{{public_path('/avatars/comquas.png')}}" width="100px" height="100px" align="right">
<h1>{{ $title }}</h1>
<hr>
<h2>Pay Slip</h3><br>
<table>

@foreach($users as $user)

<tr>
  <td>Employee-Name  </td><td>:</td>
  <td> {{ $user->name }}</td>
</tr>

<tr>
  <td>Position </td><td>:</td>
  <td> {{ $user->position->title }}</td>
</tr>

<tr>
  <td>Salary</td><td>:</td>
  <td>{{ $user->salary }} Ks</td>
</tr>

<tr>
  <td>Leave  </td><td>:</td>
  <td> {{ $annual }} day taken</td>
</tr>

<tr>
  <td>Remain-Leave  </td><td>:</td>
  <td>{{ $user->no_of_leave - $annual }} day left</td>
</tr>

<tr>
  <td> Sick-Leave  </td><td>:</td>
  <td>{{ $sick }} day taken</td>
</tr>

<tr>
  <td>Remain Sick-Leave  </td><td>:</td>
  <td>{{ $user->sick_leave - $sick }} day left</td>
</tr> 
 

@endforeach

</table>




</body>
</html>



