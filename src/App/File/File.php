<?php
declare (strict_types=1);

namespace App\File;
/*
 File is a simply file class for reading and writing file that can used in foreach-loops just like SplFileObject. The interface Traversable and Iterator are required to support
 "foreach($fileObject as $line)" syntax, and Traversable's abstract method must be declared and implemented within File itself: you cannot automatically forward the implmentation
 using the magic method __call() to $this->file.

 You can call all the methods of \SplFileInfo; however, unlike \SplFileObject that implements the \SeekableIterator and \RecusriveIterator interfaces, you cannot call seek() method or
 the \RecursiveIterator methods getChildren() or hasChildren(). The RecursiveIterator methods are called implicitly by the PHP engine--but I don't know when that is, what the use-case is.
 */ 
 
class File implements \Traversable, \Iterator {

    private $line_no;

    private $file;    // type = \SplFileObject  

    public function __construct(string $filename, string $mode = 'r')
    {
       $this->file = new \SplFileObject($filename, $mode);

       $this->file->setFlags(\SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);

       $this->line_no = 1;
    }

    // Magic method  __call ()  forwards
    public function __call ($method, $arguments)
    {
        return call_user_func_array(array($this->file, $method), $arguments); 
    }    
     
    public function get_lineno() : int
    {
        return $this->line_no;
    }
   
    public function current() : string
    {
      return $this->file->current(); 
    }

    public function rewind() 
    {
        $this->file->rewind();

        $this->line_no = 1;

        return;
    }

    public function valid() : bool
    {
        return $this->file->valid();
    }
  
    public function key() : int  
    {
        return $this->line_no;
    }

    public function next() 
    {
        $this->file->next();

        if ($this->file->valid()) {

            ++$this->line_no; 
        }
        
        return;
    }
}
