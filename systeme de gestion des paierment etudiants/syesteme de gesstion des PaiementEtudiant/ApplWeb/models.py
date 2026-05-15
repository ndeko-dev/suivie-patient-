from django.db import models

# Create your models here.
class Etudiant(models.Model):
    nom=models.CharField(max_length=30)
    postnom=models.CharField(max_length=30)
    pren=models.CharField(max_length=30)
    sex=models.CharField(max_length=10)
    lieuN=models.CharField(max_length=20)
    datNais=models.DateField()

class Paiement(models.Model):
    mont=models.IntegerField()
    unitMon=models.CharField(max_length=10)
    motif=models.CharField(max_length=30)
    datPaie=models.DateField()
    Et=models.ForeignKey(Etudiant,on_delete=models.CASCADE)


