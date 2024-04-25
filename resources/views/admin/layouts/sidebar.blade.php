<style>
  /* body {
  font-family: "Rubik", sans-serif;
} */



.div-center {
  display: flex;
  justify-content: center;
  margin: 80px 0px;
}



.profile-content {
  width: min(400px , 100%);
  border-radius: 10px;
  background-color: #3c8dbc;
  background-image: linear-gradient(62deg, #3c8dbc 0%, #2f3030c9 100%);
  transition-property: opacity, transform;
  transition-duration: calc(700ms);
  transition-delay: 0s;
}

.details {
  position: relative;
  width: 100%;
}

.profile-name {
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}



.profile-name h4 {
  /* font-size: 16px; */
  white-space: nowrap;
}

.profile-name p {
  /* font-size: 14px; */
  font-weight: 300;
}

.avatar {
  width: 100%;
  text-align: center;
}


.profile-name {
  transition-property: opacity, transform;
  transition-duration: calc(700ms / 2);
  transition-delay: 0s;
  padding-bottom: 10px;
}

.logoutsty{
  color: #ffd700;
}
.logoutsty:hover{
  color: black;
}

.socails {
    display: flex;
    gap: 0.5em;

    img {
        width: auto;
        transition-property: opacity, transform;
        transition-duration: calc(700 / 3);
        transition-delay: 0s;
    }
}
</style>
<aside class="control-sidebar "  >
  <div class="container">
    <div class="div-center">
        <div class="profile-content">
            <div class="avatar">
                <img src="https://img.icons8.com/?size=192&id=20751&format=png" alt="avatar" />
            </div>
            <div class="details">
                <div class="profile-name">
                    <h4 style="color: white;">{{$auth->name." ".$auth->surname }}</h4>
                    <p style="color: white;">{{ $auth->email }}</p>
                    <p style="color: white;">{{ $auth->role }}</p>
                    <form action="{{route('logout')}}" method="POST">
                      @csrf
                      <a href="{{route('logout')}}" class="nav-link logoutsty" onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="nav-icon fas fa-power-off"></i>
                        {{ __('main.Logout') }}
                      </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</aside>
