from django.shortcuts import render,redirect
from .models import *
from django.db.models import Sum
from django.db.models.functions import Coalesce

# Create your views here.

def home(request):
    et=Etudiant.objects.all()
    return render(request,"Appl/index.html",{'e':et})

def chargInscription(request):
    request.session['proc']='insc'
    return render(request,"Appl/inscription.html")

def chargInscriptionMod(request,idE):
    request.session['proc']='mod'
    et=Etudiant.objects.get(pk=idE)
    return render(request,"Appl/inscription.html",{'e':et})

def enregistrerEt(request):
    Et=Etudiant()
    Et.nom=request.POST.get("txtno")
    Et.postnom=request.POST.get("txtpost")
    Et.pren=request.POST.get("txtpren")
    Et.sex=request.POST.get("txtSe")
    Et.lieuN=request.POST.get("txtlie")
    Et.datNais=request.POST.get("txtdat")
    Et.save()
    return redirect("/Appl/acc")
    #return render(request,"Appl/index.html")
    
def supprimerEt(request,id):
    et=Etudiant.objects.get(pk=id)
    et.delete()
    return redirect('/Appl/acc')

def modifierEt(request,id):
    Et=Etudiant()
    Et=Etudiant.objects.get(pk=id)
    Et.nom=request.POST.get("txtno")
    Et.postnom=request.POST.get("txtpost")
    Et.pren=request.POST.get("txtpren")
    Et.sex=request.POST.get("txtSe")
    Et.lieuN=request.POST.get("txtlie")
    Et.datNais=request.POST.get("txtdat")
    Et.save()
    return redirect("/Appl/acc")

def chargLstEtPaie(request):
    et=Etudiant.objects.all()
    return render(request,"Appl/lstEtudiant.html",{'e':et})

def chargPaie(request,id):
    et=Etudiant.objects.get(pk=id)
    return render(request,"Appl/paiement.html",{'e':et})

def enregistrerPaie(request,id):
    p=Paiement()
    p.mont=request.POST.get("txtmont")
    p.unitMon=request.POST.get("txtUnit")
    p.motif=request.POST.get("txtmot")
    p.datPaie=request.POST.get("txtdat")
    p.Et=Etudiant.objects.get(pk=id)
    p.save()
    return redirect("/Appl/lstE")

def chargSituationGlob(request):
    et=Paiement.objects.values('Et_id','unitMon','Et__nom','Et__postnom','Et__pren').annotate(somme=Coalesce(Sum('mont'),0))
    return render(request,"Appl/sitGlob.html",{'e':et})

def chargDetails(request,id):
    et=Etudiant.objects.get(pk=id)
    p=Paiement.objects.filter(Et_id=id)
    return render(request,"Appl/details.html",{'e':et,'pa':p})

def chargSituationGlobInsolv(request):
    et=Etudiant.objects.annotate(somme=Coalesce(Sum('paiement__mont'),0))
    return render(request,"Appl/sitGlobIns.html",{'e':et})