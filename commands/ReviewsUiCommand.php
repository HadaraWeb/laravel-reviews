<?php

namespace Mtvs\Reviews\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class ReviewsUiCommand extends Command
{
	protected $signature = 'reviews:ui';

    protected $description = 'Install the reviews UI files';

    public function handle()
    {
        $filesystem = new Filesystem();

        $filesystem->copyDirectory(
            __DIR__.'/../stubs/ui', 
            resource_path('js/components/reviews')
        );

        $filesystem->copyDirectory(
            __DIR__.'/../stubs/resources/css',
            public_path('css')
        );       

        $filesystem->copyDirectory(
            __DIR__.'/../stubs/resources/font',
            public_path('font')
        );
        
        $this->info('The reviews UI files were installed successfully.');    
        $this->comment('Don\'t forget to register the UI components.');
        $this->comment('Don\'t forget to include the style sheet file.');
        $this->newLine();

        $this->call('approval:ui');
    }
}
