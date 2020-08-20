#!/bin/bash

rm -rf sezzle-magento2 && mkdir -p sezzle-magento2/Sezzle/Sezzlepay && \
cp README.md sezzle-magento2 && \
cp -R app/* sezzle-magento2/Sezzle/Sezzlepay/ && \
zip -r sezzle-magento2.zip sezzle-magento2 && \
rm -rf sezzle-magento2