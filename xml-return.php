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
              <!-- Get users from te database to fill the dropdown-->

                <option selected disabled>Select a display name</option>

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
    <div id="messageBox" style="overflow-y: scroll; height:400px;">
    </div>
    
    <a href="index.html" style="color:black; float:left;">Back to index</a>

    <script>
        // Prevents submission on enter key press
        function checkKey(e)
        {
          var code = e.keyCode;
          if(code == 13)
          {
            e.preventDefault();
            checkData();
          }
        }
        document.getElementById("messageText").addEventListener('keypress', checkKey);
    </script>

    <script>
      window.setInterval(function(){getMessages()}, 1000);

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
      // Get messages from database

      var latest_message_id = 0; // Initially set to 0 to get all messages (which starts at 1) 

      function getMessages()
      { 
        var request = "get-messages-xml.php?message_id=" + latest_message_id;
        var xhr = new XMLHttpRequest();
        xhr.open("GET", request);

        xhr.onreadystatechange = function()
        {
          if(xhr.readyState == 4 && xhr.status == 200)
          {
            var incoming = xhr.responseXML;

            var root = incoming.getElementsByTagName('results');
            
            if (root[0].childElementCount != 0) // If there are no children, there are no more messages to fetch so nothing happens.
            {
              latest_message_id = root[0].getElementsByTagName('id')[0].firstChild.data; // The ltest message id is updated, so if no messages are added nothing is fetched
              var results = incoming.getElementsByTagName('message');
              var nr = results.length;
              
              var chatBox = "";
              for (var i = 0; i < nr; i++)
              {
                var image_tag = results[i].getElementsByTagName('image_tag')[0].firstChild.data;
                var name = results[i].getElementsByTagName('name')[0].firstChild.data;
                var message_contents = results[i].getElementsByTagName('message_contents')[0].firstChild.data;
                var time = results[i].getElementsByTagName('time')[0].firstChild.data;
                chatBox += // The new message 
              `<div style=\"border: 2px solid #dedede; background-color: #f1f1f1; border-radius: 5px; padding: 30px; margin: 10px auto; width:25%;\">` + image_tag + 
              `<p style=\"font-weight:bold;\">` + name + `</p><p>` + message_contents + `</p><span style=\"float: right;color: #aaa;\">` + time + `</span></div>`;
              }
              document.getElementById("messageBox").innerHTML = chatBox + document.getElementById("messageBox").innerHTML; // New message is added on top of the old messages
              }
          }
        };
        xhr.send(null);
      }
    </script>
    
  </body>

</html>