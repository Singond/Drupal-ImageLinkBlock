<?php
namespace Drupal\image_link\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;


/**
 * Provides an Image Link block.
 *
 * @Block(
 *   id = "image_link",
 *   admin_label = @Translation("Image Link"),
 *   category = @Translation("Custom"),
 * )
 */
class ImageLink extends BlockBase implements BlockPluginInterface {

	/**
	 * {@inheritdoc}
	 */
	public function build() {
		$config = $this->getConfiguration();
		
		if (!empty($config['image_link_image'])) {
			$imageId = $config['image_link_image'][0];
			$image = File::load($imageId);
			if ($image === null) {
				$imageUrl = "";
			} else {
				$imageUrl = file_create_url($image->getFileUri());
			}
		} else {
			$imageUrl = '';
		}
		
		if (!empty($config['image_link_link'])) {
			$link = $config['image_link_link'];
		} else {
			$link = '';
		}
		
		return array(
			'#image' => $imageUrl,
			'#link' => $link,
			'#theme' => 'image_link_block',
			'#attached' => ['library' => ['image_link/common']],
		);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Drupal\Core\Block\BlockBase::blockForm()
	 */
	public function blockForm($form, FormStateInterface $form_state) {
		$form = parent::blockForm($form, $form_state);
		$config = $this->getConfiguration();
		
		$form['image'] = array(
			'#type' => 'managed_file',
			'#title' => $this->t('Image'),
			'#description' => $this->t('The image to be displayed as the link'),
			'#default_value' => isset($config['image_link_image'])
					? $config['image_link_image'] : null,
			'#upload_location' => 'public://images/',
			'#required' => true,
		);
		
		$form['link'] = array(
			'#type' => 'textfield',
			'#title' => $this->t('Link'),
			'#description' => $this->t('The URL the image will link to'),
			'#default_value' => isset($config['image_link_link'])
					? $config['image_link_link'] : '',
			'#required' => true,
		);
		return $form;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Drupal\Core\Block\BlockBase::blockSubmit()
	 */
	public function blockSubmit($form, FormStateInterface $form_state) {
		parent::blockSubmit($form, $form_state);
		$image = $form_state->getValue('image')[0];
		$imageFile = File::load($image);
		if (!empty($imageFile)) {
			$imageFile->setPermanent();
			$imageFile->save();
			$file_usage = \Drupal::service('file.usage');
			$id = \Drupal::currentUser()->id();
			$file_usage->add($imageFile, 'image_link', 'image_link', $id);
		}

		$values = $form_state->getValues();
		$this->configuration['image_link_image'] = $values['image'];
		$this->configuration['image_link_link'] = $values['link'];
	}
}
?>