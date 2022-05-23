# qweb ImageVariants

## Introduction

This package provides some useful implementations for the neos cms image variants feature.

## Usage

The VariantImageUri implementation is extending the ImageUriImplementation and so you have the full set of properties 
of the ImageUriImplementation. See [documentation](https://neos.readthedocs.io/en/stable/References/NeosFusionReference.html#neos-neos-imageuri)

The packages provides a new property "variant" where you can tell the component which of your variants you want to use her.

```
Qweb.ImageVariants:VariantImageUri {
    asset = ${asset} # the asset you want to get variants from
    variant = ${variant} # the variant identifier e.g. 'twobythree'
}
```

The same is provided for the Neos.Neos:ImageTag fusion component. See [documentation](https://neos.readthedocs.io/en/stable/References/NeosFusionReference.html#neos-neos-imagetag)

```
Qweb.ImageVariants:VariantImageTag {
    asset = ${asset} # the asset you want to get variants from
    variant = ${variant} # the variant identifier e.g. 'twobythree'
}
```