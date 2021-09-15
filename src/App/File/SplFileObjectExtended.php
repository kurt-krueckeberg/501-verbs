<?php
namespace App\File;
 
class SplFileObjectExtended extends \SplFileObject   { 

    private $line_no;

    public function __construct(string $filename, string $mode = 'r')
    {
       parent::__construct($filename, $mode);

       parent::setFlags(\SplFileObject::READ_AHEAD | \SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);

       $this->line_no = 1;
    }

    public fgets(): string
    {
      $rc = parent::fgets();
      if ($rc === false)
           return $rc;

      ++$this->line_no;  
      return $rc;
    }

    public fread(int $length): string | false
    {
      $rc = parent::fread($length);
      if ($rc === false)
           return $rc;

      ++$this->line_no;  
      return $rc;
    }

    public function current() : string
    {
      $str = parent::current(); 
      return $str;
    }

    public function rewind() 
    {
        parent::rewind();
        $this->line_no = 1;
        return;
    }

    public function key() : int  
    {
        return $this->line_no;
    }

    public function next() 
    {
        parent::next();

        if (parent::valid()) {

            ++$this->line_no; 
        }
        
        return;
    }
}
