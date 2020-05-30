<?php
     include   'FPDF.php';

     class PDF_MC_Table extends FPDF{
          var $widths;
          var $aligns;
          var $paperSize;
     
        function setPaperSizeWhenPageBreak($paperSize){
            $this->paperSize    =   $paperSize;
        }
    // Inline Image
    function InlineImage($file, $x=null, $y=null, $w=0, $h=0, $type='', $link='')
    {
        // ----- Code from FPDF->Image() -----
        // Put an image on the page
        if($file=='')
            $this->Error('Image file name is empty');
        if(!isset($this->images[$file]))
        {
            // First use of this image, get info
            if($type=='')
            {
                $pos = strrpos($file,'.');
                if(!$pos)
                    $this->Error('Image file has no extension and no type was specified: '.$file);
                $type = substr($file,$pos+1);
            }
            $type = strtolower($type);
            if($type=='jpeg')
                $type = 'jpg';
            $mtd = '_parse'.$type;
            if(!method_exists($this,$mtd))
                $this->Error('Unsupported image type: '.$type);
            $info = $this->$mtd($file);
            $info['i'] = count($this->images)+1;
            $this->images[$file] = $info;
        }
        else
            $info = $this->images[$file];

        // Automatic width and height calculation if needed
        if($w==0 && $h==0)
        {
            // Put image at 96 dpi
            $w = -96;
            $h = -96;
        }
        if($w<0)
            $w = -$info['w']*72/$w/$this->k;
        if($h<0)
            $h = -$info['h']*72/$h/$this->k;
        if($w==0)
            $w = $h*$info['w']/$info['h'];
        if($h==0)
            $h = $w*$info['h']/$info['w'];

        // Flowing mode
        if($y===null)
        {
            if($this->y+$h>$this->PageBreakTrigger && !$this->InHeader && !$this->InFooter && $this->AcceptPageBreak())
            {
                // Automatic page break
                $x2 = $this->x;
                $this->AddPage($this->CurOrientation,$this->CurPageSize,$this->CurRotation);
                $this->x = $x2;
            }
            $y = $this->y;
            $this->y += $h;
        }

        if($x===null)
            $x = $this->x;
        $this->_out(sprintf('q %.2F 0 0 %.2F %.2F %.2F cm /I%d Do Q',$w*$this->k,$h*$this->k,$x*$this->k,($this->h-($y+$h))*$this->k,$info['i']));
        if($link)
            $this->Link($x,$y,$w,$h,$link);
        # -----------------------

        // Update Y
        $this->y += $h;
    }


     function SetWidths($w)
     {
         //Set the array of column widths
         $this->widths=$w;
     }
     
     function SetAligns($a)
     {
         //Set the array of column alignments
         $this->aligns=$a;
     }
     
     function Row($data)
     {
         //Calculate the height of the row
         $nb=0;
         for($i=0;$i<count($data);$i++)
             $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
         $h=10*$nb;
         //Issue a page break first if needed
         $this->CheckPageBreak($h, $this->paperSize);
         //Draw the cells of the row
         for($i=0;$i<count($data);$i++)
         {
             $w=$this->widths[$i];
             $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
             //Save the current position
             $x=$this->GetX();
             $y=$this->GetY();
             //Draw the border
             $this->Rect($x,$y,$w,$h);
             //Print the text
             $this->MultiCell($w,10,$data[$i], 0,$a);
             //Put the position to the right of the cell
             $this->SetXY($x+$w,$y);
         }
         //Go to the next line
         $this->Ln($h);
     }
     
     function CheckPageBreak($h, $paperSize)
     {
         //If the height h would cause an overflow, add a new page immediately
         if($this->GetY()+$h>$this->PageBreakTrigger)
             $this->AddPage($this->CurOrientation, $paperSize);
     }
     
     function NbLines($w,$txt)
     {
         //Computes the number of lines a MultiCell of width w will take
         $cw=&$this->CurrentFont['cw'];
         if($w==0)
             $w=$this->w-$this->rMargin-$this->x;
         $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
         $s=str_replace("\r",'',$txt);
         $nb=strlen($s);
         if($nb>0 and $s[$nb-1]=="\n")
             $nb--;
         $sep=-1;
         $i=0;
         $j=0;
         $l=0;
         $nl=1;
         while($i<$nb)
         {
             $c=$s[$i];
             if($c=="\n")
             {
                 $i++;
                 $sep=-1;
                 $j=$i;
                 $l=0;
                 $nl++;
                 continue;
             }
             if($c==' ')
                 $sep=$i;
             $l+=$cw[$c];
             if($l>$wmax)
             {
                 if($sep==-1)
                 {
                     if($i==$j)
                         $i++;
                 }
                 else
                     $i=$sep+1;
                 $sep=-1;
                 $j=$i;
                 $l=0;
                 $nl++;
             }
             else
                 $i++;
         }
         return $nl;
     }
     }
