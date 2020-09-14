<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use OpenDialogAi\ConversationBuilder\Conversation;

class ImportConversations extends Command
{
    protected $signature = 'conversations:import {conversation?} {--y|yes} {--activate|activate}';

    protected $description = 'Import all conversations';

    public function handle()
    {
        $conversationName = $this->argument('conversation');

        if ($this->option('yes')) {
            $continue = true;
        } elseif ($conversationName) {
            $continue = $this->confirm(
                sprintf(
                    'Do you want to import conversation %s?',
                    $conversationName
                )
            );
        } else {
            $continue = $this->confirm(
                'This will import or update all conversations. Are you sure you want to continue?'
            );
        }

        if ($continue) {
            $activate = ($this->option('activate')) ? true : false;

            if ($conversationName) {
                $this->importConversation($conversationName . '.conv', $activate);
            } else {
                $files = preg_grep('/^([^.])/', scandir(base_path('resources/conversations')));

                foreach ($files as $conversationFileName) {
                    $this->importConversation($conversationFileName, $activate);
                }
            }

            $this->info('Import of conversations finished');
        } else {
            $this->info('OK, not running');
        }
    }

    protected function importConversation($conversationFileName, $activate): void
    {
        $conversationName = preg_replace('/.conv$/', '', $conversationFileName);

        $this->info(sprintf('Importing conversation %s', $conversationName));

        $filename = base_path("resources/conversations/$conversationFileName");
        $model = file_get_contents($filename);

        $newConversation = Conversation::firstOrNew(['name' => $conversationName]);
        $newConversation->fill(['model' => $model]);
        $newConversation->save();

        if ($activate) {
            $this->info(sprintf('Activating conversation with name %s', $newConversation->name));
            $newConversation->activateConversation();
        }
    }
}
