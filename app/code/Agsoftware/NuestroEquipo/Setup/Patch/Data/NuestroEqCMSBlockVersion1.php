<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Agsoftware\NuestroEquipo\Setup\Patch\Data;

use Magento\Cms\Api\BlockRepositoryInterface;
// use Magento\Cms\Api\Data\BlockInterface;
use Magento\Cms\Model\BlockFactory;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

/**
 */
class NuestroEqCMSBlockVersion1
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
        BlockRepositoryInterface $blockRepository,
        BlockFactory $blockFactory
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
 <div class="contenedor_nuestroEq"> <div class="contenedor_nuestroEq_uipo"> <span> <h1>OUR</h1> <p>TEAM</p> </span> <div class="contenedor_perfil"> <img src="{{media url=wysiwyg/banner/persona.png}}" alt="" /> <div class="contenedor_perfil_text"> <h2>Olman Hernández</h2> <hr> <a href=""><img src="{{media url=wysiwyg/banner/in.png}}" alt="" /></a> <p> Describir es explicar, de manera detallada y ordenada, cómo son las personas, animales, lugares, objetos, etc. ... La descripción también se puede definir como la representación verbal de los rasgos propios de un objeto. Al describir una persona, un animal, un sentimiento, etc. </p> </div> </div> <div class="contenedor_perfil"> <img src="{{media url=wysiwyg/banner/persona.png}}" alt="" /> <div class="contenedor_perfil_text"> <h2>Rúfin Perez</h2> <hr> <a href=""><img src="{{media url=wysiwyg/banner/in.png}}" alt="" /></a> <p> Describir es explicar, de manera detallada y ordenada, cómo son las personas, animales, lugares, objetos, etc. ... La descripción también se puede definir como la representación verbal de los rasgos propios de un objeto. Al describir una persona, un animal, un sentimiento, etc. </p> </div> </div> <div class="contenedor_perfil"> <img src="{{media url=wysiwyg/banner/persona.png}}" alt="" /> <div class="contenedor_perfil_text"> <h2>Maria Lopez</h2> <hr> <a href=""><img src="{{media url=wysiwyg/banner/in.png}}" alt="" /></a> <p> Describir es explicar, de manera detallada y ordenada, cómo son las personas, animales, lugares, objetos, etc. ... La descripción también se puede definir como la representación verbal de los rasgos propios de un objeto. Al describir una persona, un animal, un sentimiento, etc. </p> </div> </div> <div class="contenedor_perfil"> <img src="{{media url=wysiwyg/banner/persona.png}}" alt="" /> <div class="contenedor_perfil_text"> <h2>John McCleen</h2> <hr> <a href=""><img src="{{media url=wysiwyg/banner/in.png}}" alt="" /></a> <p> Describir es explicar, de manera detallada y ordenada, cómo son las personas, animales, lugares, objetos, etc. ... La descripción también se puede definir como la representación verbal de los rasgos propios de un objeto. Al describir una persona, un animal, un sentimiento, etc. </p> </div> </div> <div class="contenedor_perfil"> <img src="{{media url=wysiwyg/banner/persona.png}}" alt="" /> <div class="contenedor_perfil_text"> <h2>Amarilis Estrada</h2> <hr> <a href=""><img src="{{media url=wysiwyg/banner/in.png}}" alt="" /></a> <p> Describir es explicar, de manera detallada y ordenada, cómo son las personas, animales, lugares, objetos, etc. ... La descripción también se puede definir como la representación verbal de los rasgos propios de un objeto. Al describir una persona, un animal, un sentimiento, etc. </p> </div> </div> </div> </div>
HTML;
        $blockData = [
            'title' => 'Nuestro',
            'identifier' => 'equipo',
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
