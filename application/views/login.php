<script>
    var valid = false;

    $(document).ready(function() {
        $('.ui.form').form({
            fields: {
                username: { identifier: 'username', rules: [{
                    type   : 'empty',
                    prompt : 'Please enter your username'
                }]},
                password: { identifier  : 'password', rules: [{
                    type   : 'empty',
                    prompt : 'Please enter your password'
                }, {
                    type   : 'length[1]',
                    prompt : 'Your password must be at least 6 characters'
                }]}
            },
            onSuccess: function(event) {    
                event.preventDefault();
                $.post("login/process", 
                    { username: $('#username').val(), password: $('#password').val() },
                    function(data, status) {
                        var res = JSON.parse(data);
                        if (res.status) {
                            location.reload();
                        } else {
                            swal(res.error);
                        }
                    }
                );
            }
        });
      
    });
</script>

<div class="ui one column middle aligned center aligned grid" style="margin-top:70px">
  <div class="seven wide column">
    <h2 class="ui red image header">
      <img src="assets/images/logo.png" class="image">
      <div class="content">
        Log-in to your account
      </div>
    </h2>
    <form class="ui medium form">
      <div class="ui stacked segment">
        <div class="field">
          <div class="ui left icon input">
            <i class="user icon"></i>
            <input type="text" id="username" name="username" placeholder="Username">
          </div>
        </div>
        <div class="field">
          <div class="ui left icon input">
            <i class="lock icon"></i>
            <input type="password" id="password" name="password" placeholder="Password">
          </div>
        </div>
        <div class="ui fluid medium red submit button">Login</div>
      </div>

      <div class="ui error message"></div>

    </form>
  </div>
</div>
