<?php
require '../connexion.php';

if(isset($_POST['submit'])){
    $ville = $_POST['ville'];
    $pays = $_POST['pays'];

    $sql = "INSERT INTO destination(ville,pays) VALUES(?,?)";
    $pdo->prepare($sql)->execute([$ville,$pays]);

    header("Location: liste.php");
}
?>

<h2>Ajouter destination</h2>

<form method="POST">

Ville:
<input type="text" name="ville" required><br><br>

Pays:
<input type="text" name="pays" required><br><br>

<button name="submit">Soumettre</button>

</form>

<br>
<a href="liste.php">Retour</a>
<style>
  body{text-align:center;font-family:Arial;
  margin-top:115px;
 background: #93b6b3;
}
input,select{
    padding:10px;
    margin:5px;}
 button{padding:10px;background:#007BFF;
color:white;
border:none;}  
.container{
background:white;
padding:30px;
border-radius:12px;
width:400px;
box-shadow:0 10px 25px rgba(141, 78, 78, 0.3);
text-align:center;
}

/* TITRE */
h2{
margin-bottom:20px;
color:#333;
}

/* INPUT */
input,select{
width:90%;
padding:10px;
margin:8px 0;
border:1px solid #140707;
border-radius:5px;
}

/* BUTTON */
button{
width:95%;
padding:12px;
background:#007BFF;
color:white;
border:none;
border-radius:5px;
cursor:pointer;
font-size:16px;
transition:0.3s;
}

button:hover{
background:#0056b3;
}

/* LINK */
a{
display:block;
margin-top:10px;
text-decoration:none;
color:#007BFF;
}

/* MESSAGE */
.success{
color:green;
margin-bottom:10px;
}

.error{
color:red;
margin-bottom:10px;
}
</style>