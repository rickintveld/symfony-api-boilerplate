<?php

namespace App\Serializer;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Context\Normalizer\DateTimeNormalizerContextBuilder;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ObjectSerializer implements SerializerInterface
{
    private Serializer $serializer;

    public function __construct()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

        $this->serializer = new Serializer([new ObjectNormalizer($classMetadataFactory)], [new JsonEncoder()]);
    }

    public function serialize(mixed $data, string $format, array $context = []): string
    {
        $contextBuilder = (new DateTimeNormalizerContextBuilder())->withFormat('d-m-Y');

        $contextBuilder = (new ObjectNormalizerContextBuilder())
            ->withGroups($context)
            ->withContext($contextBuilder);

        return $this->serializer->serialize($data, $format, $contextBuilder->toArray());
    }

    public function deserialize(mixed $data, string $type, string $format, array $context = []): mixed
    {
        /** @psalm-suppress MixedReturnStatement */
        return $this->serializer->deserialize($data, $type, $format, $context);
    }
}
