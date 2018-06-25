<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="/css/style.css" />
    <script src="/js/jquery-1.6.2.js" type="text/javascript"></script>
    <script type="text/javascript"> 
        $(document).ready(function() {
            $("#nice_input").keydown(function(e) {
                if(e.keyCode === 13) {
                    send_url();
                }
            });
        });

        function send_url() {
            var regexp = /(https?:\/\/)?(www\.)?([-а-яa-z0-9_\.]{2,}\.)(рф|[a-z]{2,6})((\/[-а-яa-z0-9_]{1,})?\/?([a-z0-9_-]{2,}\.[a-z]{2,6})?(\?[a-z0-9_]{2,}=[-0-9]{1,})?((\&[a-z0-9_]{2,}=[-0-9]{1,}){1,})?)/i;
            var user_url = $("#nice_input").val();
            var validate = regexp.test(user_url);
            var regexp2 = /(https?:\/\/)/i
            
            if(!regexp2.test(user_url)){
                user_url = prepare_link(user_url);
            }
            
            if(validate){
                $.ajax(
                  {
                      type: "POST",
                      data: {name: user_url, 
                            browser: navigator.userAgent},
                      url: "/application/controllers/controller_ajax.php",
                      success: function(recv){
                          add_text("#post_data", recv);
                         //alert(recv);
                      }
                  }  
                );
            }else{
                add_text("#post_data", "Your link is incorrect");
            }
        }

        function add_text(id, text){
            $(id).text(text);
            $(id).attr("href", text);
        }

        function prepare_link(url){
            return "http://" + url;
        }
	</script>
</head>
<body>
    <div id="footer" >
        <input id="nice_input" type="url" required placeholder="Your URL...">
        <a href="#" class="button7" onclick=send_url()>Reduce</a>
    </div>
    <div>
        <a href="#" id="post_data"></a>
    </div>
</body>
</html>