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

    $app->post("/create_contact", function() use ($app) {
        $new_contact = new Contact($_POST['name'], $_POST['phone'], $_POST['email'], $_POST['address']);
        $new_contact->save();
        return $app['twig']->render('create_contact.html.twig', array('newcontact' => $new_contact));
    });

    $app->post("/delete_last_contact", function() use ($app) {
// I Saw this if(isset($stuff)) form on the internet, I'm not exactly sure if it's necessary but I imagine it is if I want to change the $_SESSION variable
        if(isset($_POST['delete'])){
            $deleted_contact = new Contact(max($_SESSION['list_of_contacts'])->getName(), max($_SESSION['list_of_contacts'])->getPhone(), max($_SESSION['list_of_contacts'])->getEmail(), max($_SESSION['list_of_contacts'])->getAddress());
            $n = count($_SESSION['list_of_contacts']);
            unset($_SESSION['list_of_contacts'][$n-1]);
        }
//
        return $app['twig']->render('delete_last_contact.html.twig', array('deletedcontact' => $deleted_contact));
    });

    $app->post("/delete_all_contacts", function() use ($app) {
        Contact::deleteAll();
        return $app['twig']->render('delete_all_contacts.html.twig');
    });

    return $app;

?>
