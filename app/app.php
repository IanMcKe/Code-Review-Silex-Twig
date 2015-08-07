<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Contact.php";

    session_start();
    if(empty($_SESSION['list_of_contacts'])){
        $_SESSION['list_of_contacts'] = array();
    }

    $app = new Silex\Application();
    $app['debug'] = true;
    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    $app->get("/", function() use ($app) {
        return $app['twig']->render('contacts.html.twig', array('contacts' => Contact::getAll()));
    });

    $app->post("create_contact", function() use ($app) {
        $new_contact = new Contact($_POST['name'], $_POST['phone'], $_POST['address']);
        $new_contact->save();
        return $app['twig']->render('create_contact.html.twig', array('newcontact' => $new_contact));
    });

    $app->post("delete_all_contacts", function() use ($app) {
        Contact::deleteAll();
        return $app['twig']->render('delete_all_contacts.html.twig');
    });

    return $app;

?>
