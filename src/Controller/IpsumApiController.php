<?php

namespace KnpU\LoremIpsumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use KnpU\LoremIpsumBundle\KnpUIpsum;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use KnpU\LoremIpsumBundle\Event\FilterApiResponseEvent;
use KnpU\LoremIpsumBundle\Event\KnpULoremIpsumEvents;

/**
 * Этот контроллер не предоставляется юзеру бандла (как, н-р, в 
 * https://github.com/SymfonyCasts/reset-password-bundle/blob/master/docs/manual-setup.md ,
 * где создаётся контроллер спец-но для юзера в папке "src/Controller"). Он существует лишь внутри 
 * бандла и принимает запросы от юзера, которые тот производит уже из общего прилож-я.
 * 
 * Usage:
 * http://first-project/api/ipsum/ (см. "knpu_lorem_ipsum.yaml")
 */
class IpsumApiController extends AbstractController
{
    private $knpUIpsum;
    
    private $eventDispatcher;

    public function __construct(KnpUIpsum $knpUIpsum, ?EventDispatcherInterface $eventDispatcher)
    {
        $this->knpUIpsum = $knpUIpsum;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function index()
    {
        $data = [
            'paragraphs' => $this->knpUIpsum->getParagraphs(),
            'sentences' => $this->knpUIpsum->getSentences(),
        ];
        
        $event = new FilterApiResponseEvent($data);
        if ($this->eventDispatcher) {
            
            // Dispatches an event to all registered listeners/subscribers.
            // Т.е., ц-тральный диспетчер ($this->eventDispatcher) запускает все обработчики
            // (листнеры или сабскайберы) соб-я KnpULoremIpsumEvents::FILTER_API, передавая этим обработчикам
            // объект $event, кот-ый я как разр-чик бандла инициализировала выше, добавив в него полезный массив
            // $data, с кот-ым юзер моего бандла будет работать (т.е., исп-вать методы объекта $event, кот-ые я
            // реализовала, н-р, getData()/setData()).
            // @param object      $event     The event to pass to the event handlers/listeners
            // @param string|null $eventName The name of the event to dispatch. If not supplied, the class of $event should be used instead.
            $this->eventDispatcher->dispatch($event, KnpULoremIpsumEvents::FILTER_API);
        }
        
        return $this->json($event->getData());
    }

}
