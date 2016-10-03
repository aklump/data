# Data

## Summary
Provides a common means for getting data from objects or arrays with default option.

## Rationale
Given a multidimensional array, in vanilla PHP you will do this:

    print isset($multi_array['do']['re']) ? $multi_array['do']['re'] : 'default';

Using this class you would do this:

    $data = new Data;
    print $data->get($multi_array, 'do.re', 'default');

Not too impressive... but wait... imagine when you have complex objects, like say a Drupal 7 field value translated into spanish.

    print isset($node->field_description['es']['1']['value']) ? $node->field_description['es']['1']['value'] : '';
    
    // vs...
    
    print $data->get($node, 'field_description.1.value', '');
    
Or when you need to work in Drupal 8 for a few days.

    print isset($node->field_description) ? $node->get('field_description')->get(1)->value : '';
    
    vs.
    
    print $data->get($node, 'field_description.1.value', '');
    
This is where a consistent interface approach starts to make sense.  By the way, there is a drupal module that uses a different implementation of this class which can be found here:

## Details
    <?php
    use AKlump\Data\Data;
    
    $data = new Data;
    
    // Let's create a data subject, from which we want to pull data.
    $a = array('b' => array('c' => 'd'));
    
    // First let's pull data that exits.
    // Because $a['b']['c'] has a value 'd', it will be returned.  Default is ignored.
    $value = $data->get($a, 'b.c', 'e');
    $value === 'c';
    
    // Because $a['b']['z'] is not set then the default value comes back, 'e'.
    $value = $data->get($a, 'b.z', 'e');
    $value === 'e';
    
    // This interface works on objects or arrays, regardless.  Let's convert this to a nested object using json functions.
    $a_object = json_decode(json_encode($a));
    
    // Make the same call and you'll get the same answer.
    $value = $data->get($a, 'b.c', 'e');
    $value === 'c';

## Acknowledgments
* Thank you Aaron Jensen (https://github.com/aaronjensen) for introducting me to this concept.
* https://lodash.com/docs/4.16.2#get
