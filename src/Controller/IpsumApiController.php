<?php
namespace KnpU\LoremIpsumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use KnpU\LoremIpsumBundle\KnpUIpsum;

/**
 * Этот контроллер не предоставляется юзеру бандла (как, н-р, в 
 * https://github.com/SymfonyCasts/reset-password-bundle/blob/master/docs/manual-setup.md ,
 * где создаётся контроллер спец-но для юзера в папке "src/Controller"). Он существует лишь внутри 
 * бандла и принимает запроса от юзера, которые тот производит уже из общего прилож-я.
 */
class IpsumApiController extends AbstractController
{
    private $knpUIpsum;

    public function __construct(KnpUIpsum $knpUIpsum)
    {
        $this->knpUIpsum = $knpUIpsum;
    }

    public function index()
    {
        return $this->json([
            'paragraphs' => $this->knpUIpsum->getParagraphs(),
            'sentences' => $this->knpUIpsum->getSentences(),
        ]);
    }

}
