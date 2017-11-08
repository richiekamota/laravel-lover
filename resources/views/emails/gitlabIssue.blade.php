<h2>
  {{$user->first_name}} {{$user->last_name}} has submitted an issue
</h2>

Date: {{\Carbon\Carbon::now()}}
<br>
User text: {{$userIssue}}
<br>
URL: {{$location}}
