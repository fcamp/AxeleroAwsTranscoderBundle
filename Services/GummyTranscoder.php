<?php
namespace Axelero\AwsTranscoderBundle\Services;


use Aws\ElasticTranscoder\ElasticTranscoderClient;
use Axelero\AwsTranscoderBundle\Services\PresetProvider;

class GummyTranscoder
{
    /**
     * @var ElasticTranscoderClient
     */
    private $client;

    /**
     * @var string
     */
    private $pipelineId;

    /**
     * @var PresetProvider
     */
    private $presetProvider;

    /**
     * GummyTranscoder constructor.
     * @param string $pipelineId
     * @param \Axelero\AwsTranscoderBundle\Services\PresetProvider $presetProvider
     * @param ElasticTranscoderClient $client
     */
    public function __construct($pipelineId, PresetProvider $presetProvider, ElasticTranscoderClient $client)
    {
        $this->client = $client;
        $this->pipelineId = $pipelineId;
        $this->presetProvider = $presetProvider;
    }

    /**
     * @param string $path the file path in the input bucket (the same path will be written used in the output one)
     * @param array $outputs the Output arrays
     * @return \Guzzle\Service\Resource\Model
     */
    public function encode($path, array $outputs)
    {
        $result = $this->client->createJob([
            'PipelineId' => $this->pipelineId,
            'Input' => [
                'Key' => $this->cleanPath($path),
                'FrameRate' => 'auto',
                'Resolution' => 'auto',
                'AspectRatio' => 'auto',
                'Interlaced' => 'auto',
                'Container' => 'auto',
            ],
            'Outputs' => $this->processOutputs($path, $outputs)
        ]);

        return $result;
    }

    /**
     * Process Output options by setting the Key parameter to the one according to the documentation
     * @param string $path
     * @param array $outputs
     * @return array
     */
    private function processOutputs($path, array $outputs)
    {
        $provider = $this->presetProvider;
        //[ dirname,basename,extension,filename ]
        $info = pathinfo($path);

        array_walk($outputs, function (&$output) use ($path, $provider, $info) {
            $detail = $provider->getDetail($output['PresetId']);
            //if used a preset and no Key has been defined for output file
            //then the same path is used
            if ($detail && !$output['Key']) {
                $output['Key'] = $this->cleanPath($info['dirname'] . '/' . $info['filename'] . '.' . $detail);
            }
        });

        return $outputs;
    }

    /**
     * @param $path
     * @return string
     */
    private function cleanPath($path)
    {
        return ltrim(preg_replace('#\/{2,}#', '/', $path), '/.');
    }

}