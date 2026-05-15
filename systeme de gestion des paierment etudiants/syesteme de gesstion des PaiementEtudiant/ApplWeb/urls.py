from django.urls import path
from .views import *

urlpatterns = [
    path('',home),
    path('inscr',chargInscription),
    path('acc',home),
    path('enrE',enregistrerEt),
    path('supE/<int:id>',supprimerEt),
    path('charMod/<int:idE>',chargInscriptionMod),
    path('modE/<int:id>',modifierEt),
    path('lstE',chargLstEtPaie),
    path('chargP/<int:id>',chargPaie),
    path('enrP/<int:id>',enregistrerPaie),
    path('sit',chargSituationGlob),
    path('det/<int:id>',chargDetails),
    path('sitIns',chargSituationGlobInsolv),
]