<?php

namespace Jerive\Bundle\FileProcessingBundle\Processing\Filter;

/**
 * Description of EncodingFilter
 *
 * @author jviveret
 */
class EncodingFilter extends FilterAbstract
{
    /**
     * Input encoding
     *
     * @var string
     */
    protected $inputEncoding = 'UTF-8';

    /**
     * Output encoding
     *
     * @var string
     */
    protected $outputEncoding = 'UTF-8';

    /**
     * Do we autodetect input encoding ?
     * (if yes, input encoding setting will be discarded)
     *
     * @var bool
     */
    protected $autodetectInputEncoding = false;

    /**
     * Throw exception on encoding error ?
     *
     * @var bool
     */
    protected $exceptionOnEncodingError = true;

    /**
     * Sets the input encoding
     *
     * @param string $encoding
     */
    public function setInputEncoding($encoding)
    {
        $this->inputEncoding = strtoupper($encoding);
    }

    /**
     * Sets the output ncoding
     *
     * @param string $encoding
     */
    public function setOutputEncoding($encoding)
    {
        $this->outputEncoding = strtoupper($encoding);
    }

    /**
     * Detect the input encoding and discard the input encoding setting
     *
     * @param boolean $bool
     */
    public function setAutodetectInputEncoding($bool = true)
    {
        $this->autodetectInputEncoding = $bool;
    }

    /**
     * Reject line when encoding error or go on anyway
     *
     * @param boolean $bool
     */
    public function setExceptionOnEncodingError($bool = true)
    {
        $this->exceptionOnEncodingError = $bool;
    }

    /**
     * Converts an array from an encoding to an other encoding,
     * according to settings.
     *
     * @see FilterInterface::filter
     * @param array  $array Array of strings to convert
     * @return array
     */
    public function filter(&$row)
    {
        $return = array();

        foreach($row as $key => $string) {
            try {
                if (is_string($string)) {
                    if ($this->autodetectInputEncoding) {
                        $inputEncoding = mb_detect_encoding($string);
                    } else {
                        $inputEncoding = $this->inputEncoding;
                    }

                    $string = iconv(
                        $inputEncoding,
                        $this->outputEncoding .  '//TRANSLIT',
                        $string
                    );
                }

                $return[$key] = $string;
            } catch (\Exception $e) {
                if ($this->exceptionOnEncodingError) {
                    throw new \RuntimeException(sprintf(_('could not transcode field "%s" from %s to %s'),
                        $key,
                        $inputEncoding,
                        $this->outputEncoding
                    ));
                } else {
                    $return[$key] = $string;
                }
            }
        }

        return $return;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        $desc = array();
        if ($this->autodetectInputEncoding) {
            $desc[] = _('Autodetects input encoding');
        } else {
            $desc[] = sprintf(_('Input must be encoded in %s'), $this->inputEncoding);
        }

        $desc[] = sprintf(
            _('Errors in the transcoding to %s will be %s'),
            $this->outputEncoding,
            $this->exceptionOnEncodingError ? _('reported') : _('discarded')
        );

        return $desc;
    }
}
