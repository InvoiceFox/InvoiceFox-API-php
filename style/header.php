<html>
<body>
<style>
body {
 padding: 0px;
 margin: 0px;
   background-color: #F8F8F5
     }
body,p,input,div,p,small,pre,textarea {
  font-size: 12px;
  font-family: Arial, Helvetica;
}
div.type  {
  float: right;
  color: gray;
  font-weight: bold;
  padding-right: 10px;
}
h1 {
margin: 0px;
padding: 8px;
  background-color: #444444;
color: white;
}
h1 a, h1 span {
  font-size: 12px;
color: white;
}
h1 a {
color: #acf;
}
h1 form input, h1 form textarea {
  font-size: 12px;
  color: #664444;
}
form input, form textarea {
  border: 1px solid #bbb;
padding: 1px 2px;
}
pre.resp, pre.debug {
padding: 20px;
  background-color: #f5e1e1;
  font-size: 10px;
}
pre.debug {
  background-color: #e1e1f5;
}
div.main {
padding: 20px;
}
form div div small {
  float: right;
  text-align: right;
color: gray;
}

h3 { padding: 8px; }
form.line { margin: 3px 0; padding: 3px 8px; background-color: #e5e5d8; }
form.line input { font-size: 10px; color: gray; }
</style>
<h1>
<form style="float: right;" method="post">
  <span><?= isset($_SESSION['debugmode'])?'debug ON (<a href="?clearDebug=1">clear</a>)':'debug OFF (<a href="?setDebug=1">set</a>)'; ?> | </span>  
  <a href="">reload page</a> 
  <?= isset($_SESSION['apitoken'])?'<input type="submit" name="clearToken" value="Clear Token" />':'<input name="token" value="first set api token" size="30" /> <input type="submit" name="setToken" value="Set" />' ?>
  </form>
  InvoiceFox API :: PHP tester</h1>
  