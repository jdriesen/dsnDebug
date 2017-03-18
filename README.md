# dsnDebug
A Simple CodeIgniter Debug Library .. 150 lines of code, comments included...

### Requirement ###

- CodeIgniter 3.0.x (tested until 3.1.3) installed.

- Some basic PHP Knowledge

## Intro ##

We're all programmers, so we're all capable to write bugs :)

Hunting bugs can be annoying ...
Tons of echo's have to be written, and such echo's are messing up our code and layout...

And most of the time, echo's are not showing the debug info in the format we want to see it (it all depends on the varType... )

Even worse...
Suppose we forgot to remove an 'echo' statements... and it's showing up during a demo or when being live... 

**Toooo baaaad**, isn't it ?

This library tries to bring a solution for all these probs.


## How can we use this library ? ##

**1 ==>** Download this file, and copy it into your CodeIgniter ***application/libraries*** folder.

**2 ==>** Create a ***debug*** folder at root of your project (so, in between '***application***' and '***system***')

Result has to be like:

    /application
    /debug
    /system
    
**3 ==>** Add following line to your **application/config/autoload.php** file:

    
      $autoload['libraries'] = array(
        .....
        'dsndebug',
        ...
      );
    

    

**4 ==>** Most good developers are using a different .htaccess file for their 'development' and 'production' environment

So ... add this line to your ***.htaccess development*** file

    SetEnv CI_ENV development

(do the same in your .htaccess production file, though replace the word 'development' with 'production'.

**OK, all setup is done !!**

Now, the fun can begin ... this library can be used ALL OVER your project !

## All you have to do in your code ##

When writing a new function, just include following line below the function definition..

    function my_function_full_of_bugs() {
      $oDebug = new DSNDebug(TRUE, TRUE);
      
      $var = 'this is a string';
    
      $oDebug->write('the_label_i_want_to_give_to_my_var', $var); // will write it to a file...
      $oDebug->screen('the_label_i_want_to_give_to_my_var', $var); // will show it on the screen...
    }

### Explanation ###
You see we need 2 params (booleans) to give when instantiating the new DSNDebug variable.

- The **first parameter** (default: TRUE) will allow us to write **to the screen**.
- The **second parameter** (default: FALSE) will allow us to write **to a file** ***( !!! )***

- Via the **screen()** function we can 'show' whatever type of var info tot the screen. (showing will only be done when **1st parameter** of DSNDebug is **TRUE**) 
- Via the **write()** function we can write whatever type of var to a file (writing will only be done when **2nd parameter** of DSNDebug is **TRUE**)
 
***( !!! )*** the Debug Info will be stored in a newly created file in the debug folder.

The filename will contain some Class info as well.

### Specials ###
- You don't have to remove all your debug info when deploying your application from development to production.... 
(the ENV var in your .htaccess will take care of it !!!)

- when your function is 'bug free', just set the parameters of DSNDebug to FALSE, FALSE.

### Extra's ###
- There are some extra's in the DEBUG Library ...

- Download & read the source code... and discover the secrets ...
 
- I've added some documentation as well in the sourcecode

- Comments, suggestions and remarks more than welcome !

***Hope you'll enjoy it.*** 



Grtz,

Johnny Driesen
