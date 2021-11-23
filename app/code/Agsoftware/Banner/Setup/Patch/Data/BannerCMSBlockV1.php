<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Agsoftware\Banner\Setup\Patch\Data;

use Magento\Cms\Api\BlockRepositoryInterface;
// use Magento\Cms\Api\Data\BlockInterface;
use Magento\Cms\Model\BlockFactory;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

/**
 */
class BannerCMSBlockV1
    implements DataPatchInterface,
    PatchRevertableInterface
{
    /**
     * @var \Magento\Framework\Setup\ModuleDataSetupInterface
     */
    private $blockFactory;

    /**
     * @param \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        \Magento\Framework\Setup\ModuleDataSetupInterface $moduleDataSetup,
        BlockFactory $blockFactory,
        BlockRepositoryInterface $blockRepository
    
    ) {
        /**
         * If before, we pass $setup as argument in install/upgrade function, from now we start
         * inject it with DI. If you want to use setup, you can inject it, with the same way as here
         */
        $this->moduleDataSetup = $moduleDataSetup;
        $this->blockRepository = $blockRepository;
        $this->blockFactory = $blockFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        //The code that you want apply in the patch
        //Please note, that one patch is responsible only for one setup version
        //So one UpgradeData can consist of few data patches
        $cmsBlock = $this->blockFactory->create();

        $blockHtmlContent = <<<HTML
<div class="container-banner"><div class="slider"> <ul> <img src="{{media url=wysiwyg/pine-cone-gf86e4f3fb_1920.jpg}}" alt="" /> <img src="{{media url=wysiwyg/nature-gbc04cee73_1920.jpg}}" alt="" /> <img src="{{media url=wysiwyg/medicinal-herb-g7411d0659_1920.jpg}}" alt="" /></ul> </div> </div> <div class="container-banner-background"> <div class="container-banner-background-text"> <h1>Crea un impacto en el desarrollo de tu negoció</h1> <p>Nosotros te ayudamos en la creación de tu E-commerce en Magento la plataforma más potente del mundo</p> <button class="boton-primario">Empezar</button> <button class="boton-segundario">Aprender Mas</button> </div> </div>
HTML;
        $blockData = [
            'title' => 'Banner',
            'identifier' => 'principal',
            'stores' => [0],
            'is_active' => 1,
            'content'=> $blockHtmlContent,
        ];
        $cmsBlock->setData($blockData);
        $cmsBlock->save();
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        /**
         * This is dependency to another patch. Dependency should be applied first
         * One patch can have few dependencies
         * Patches do not have versions, so if in old approach with Install/Ugrade data scripts you used
         * versions, right now you need to point from patch with higher version to patch with lower version
         * But please, note, that some of your patches can be independent and can be installed in any sequence
         * So use dependencies only if this important for you
         */
        return [];
    }

    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        //Here should go code that will revert all operations from `apply` method
        //Please note, that some operations, like removing data from column, that is in role of foreign key reference
        //is dangerous, because it can trigger ON DELETE statement
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        /**
         * This internal Magento method, that means that some patches with time can change their names,
         * but changing name should not affect installation process, that's why if we will change name of the patch
         * we will add alias here
         */
        return [];
    }
}
