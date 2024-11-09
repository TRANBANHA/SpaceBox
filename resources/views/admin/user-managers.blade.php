@extends('admin.mylayout')

@section('title')
    Danh sách người dùng
@endsection


@section('content')
<div class="sales-boxes">
        <div class="recent-sales box">
          <div class="title">Recent Sales</div>
          <div class="sales-details">
            <ul class="details">
              <li class="topic">Date</li>
              <li><a href="#">02 Jan 2021</a></li>
              
            </ul>
            <ul class="details">
            <li class="topic">User</li>
            <li><a href="#">Alex Doe</a></li>
            
          </ul>
          <ul class="details">
            <li class="topic">Gender</li>
            <li><a href="#">Delivered</a></li>
           
          </ul>
          <ul class="details">
            <li class="topic">Trạng thái</li>
            <li><a href="#">$204.98</a></li>
            
          </ul>
          </div>
          <div class="button">
            <a href="#">See All</a>
          </div>
        </div>
        
      </div>
@endsection

