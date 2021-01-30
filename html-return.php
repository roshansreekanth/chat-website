<?php
  $db = mysqli_connect("127.0.0.1", "dbname", "password");
  $charset_set = mysqli_set_charset($db, 'utf8');
  mysqli_select_db ($db, "R00170592_db");
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Chat</title>
  </head>

  <body style="font-family: Helvetica, sans-serif;">
    <a href="add-user.html" style="color:black; float:left;">Add New User</a></p>
    
    <h1 style="text-align:center;padding-right:100px;">Live Blog</h1>

    <div style="text-align:center;">
      
      <form method="POST" id="form">
          <p><div>
            <label for="user_id">Display name:</label>

            <select name="user_id" id="user_id">
                <option selected disabled>Select a display name</option>

                <!-- Get users from te database to fill the dropdown-->

                <?php
                    $result = mysqli_query ($db, "SELECT * FROM users;");
          
                    while ($row = mysqli_fetch_array ($result))
                    {
                ?>
                
                <option value="<?php echo "{$row['id']}" ?>">
                  <?php echo "{$row['name']}"; } ?>
                </option>
            </select>
          </div></p>
        
        <p><div>
          <input type="text" name="message" id="messageText">
          <button onclick="checkData()" type="button" value="Send">Send</button>
        </div></p>

      </form>
    </div>
    
    <!-- Holds the messages -->
    <div id="messageBox" style="overflow-y: scroll; height:400px;"></div>
    
    <p><a href="index.html" style="color:black; float:left;">Back to index</a></p>

    <script>
      // Prevents submission on enter key press
      document.getElementById("messageText").addEventListener('keypress', checkKey);
      function checkKey(e)
      {
        var code = e.keyCode;

        if(code == 13)
        {
          e.preventDefault();
          checkData();
        }
      }
    </script>

    <script>
      window.setInterval(function(){getMessages()}, 1000); // Checks database every second

      // Checks if message or name is empty
      function checkData()
      {
        var dropdown = document.getElementById("user_id");
        var user = dropdown.options[dropdown.selectedIndex].value;
      
        var message = document.getElementById("messageText").value;
        
        if(user != "Select a display name")
        {
          if(message.trim().length != 0)
          {
              document.getElementById("messageText").value = null;
              document.getElementById("user_id").selectedIndex=0;
              sendData(user, message);
          }
          else
          {
            alert("Please Enter Message")
          }

        }
        else
        {
          alert("Please select User Name...")
        }
      }

      function sendData(user, message)
      {
        // Save message to database
        var formData = new FormData();
        formData.append('user_id', escape(user));
        formData.append('message', escape(message));

        var xmlho = new XMLHttpRequest();
        xmlho.open("POST", "save-message-to-db.php");
        xmlho.send(formData);
      }
    </script>

    <script>
      function getMessages()
      {
        // Get messages from database
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "get-messages-html.php");

        xhr.onreadystatechange = function()
        {
          if(xhr.readyState == 4 && xhr.status == 200)
          {
            document.getElementById("messageBox").innerHTML = xhr.responseText;
          }
        };
        xhr.send(null);
      }
    </script>
    
  </body>

</html>