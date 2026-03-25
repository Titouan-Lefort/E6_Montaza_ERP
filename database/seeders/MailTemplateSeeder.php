<?php

namespace Database\Seeders;

use App\Models\Mailtemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mailtemplates = [
            ['nom' => 'ddp', 'sujet' => 'Demande de prix {code_ddp}', 'contenu' => '<p>Bonjour,</p><p>Pourriez-vous, nous faire parvenir <strong><u>votre tarif ainsi que les délais de livraison</u></strong> suivant la pièce jointe ?</p><p>Merci d\'avance.</p><p>Cordialement,</p><p><br></p>'],
            ['nom' => 'cde', 'sujet' => 'Commande {code_cde}', 'contenu' => '<p>Bonjour,</p><p>Vous trouverez en PJ la commande</p><p>Merci de nous envoyer par retour votre AR de commande avec la date de livraison prévue</p><p>Vous en souhaitant bonne réception</p><p>Cordialement</p>']
        ];

        foreach ($mailtemplates as $mailtemplate) {
            Mailtemplate::updateOrCreate($mailtemplate);
        }
    }
}
