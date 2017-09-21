function autoWrap($text, $maxWidth, $maxHeight, $lineMargin, $fontName) {
  $image = new Imagick();
  $draw = new ImagickDraw();

  $startFontSize = round($this->height / 4);
  $fontSize = $startFontSize;

  $draw->setFont($fontName);

  $lineWidth = 10;
  $custom = false;
  
  $text = preg_replace('/\s+/', ' ', $text);
  
  while (true) {
    $draw->setFontSize($fontSize);

    $fit = false;

    while (true) {
      if ($custom == false) {
        $lines = explode("\n", wordwrap($text, $lineWidth, "\n", false));
      }

      $longestLine = 0;
      $longestLineIndex = 0;

      // Search for the longest line for the current font size
      foreach ($lines as $i => $line) {
        $fontMetrics = $image->QueryFontMetrics($draw, $line);

        if ($fontMetrics['textWidth'] > $longestLine) {
          $longestLine = $fontMetrics['textWidth'];
          $longestLineIndex = $i;

          /*
          if the longest line is longer than the width then get out
          of the outer loop without $fit = true
          */
          if ($longestLine > $maxWidth) {
            break 2;
          }
        }
      }

      $fit = true;
      $resultLines = $lines;
      $resultLineHeight = $fontMetrics['textHeight'];

      if (count($lines) == 1) {
        break;
      }

      $lineWidth++;
    }


    if ($fit) {
      $totalHeight = count($resultLines) * ($resultLineHeight + $lineMargin) - $lineMargin;

      if ($totalHeight <= $maxHeight) {
        break;
      }
    }
    $fontSize--;
  }

  return array($resultLines, $fontSize);
}
